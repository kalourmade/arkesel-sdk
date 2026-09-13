import { NetworkError } from './errors.js';

export interface HttpResponse {
  status: number;
  body: string;
}

export interface Transport {
  request(
    method: string,
    path: string,
    query?: Record<string, string | number>,
    jsonBody?: unknown
  ): Promise<HttpResponse>;
}

export class FetchTransport implements Transport {
  constructor(
    private readonly apiKey: string,
    private readonly baseUrl: string = 'https://sms.arkesel.com',
    private readonly timeoutMs: number = 30000
  ) {}

  async request(
    method: string,
    path: string,
    query?: Record<string, string | number>,
    jsonBody?: unknown
  ): Promise<HttpResponse> {
    const url = new URL(this.baseUrl.replace(/\/$/, '') + path);
    if (query) {
      for (const [key, value] of Object.entries(query)) {
        url.searchParams.set(key, String(value));
      }
    }

    const controller = new AbortController();
    const timer = setTimeout(() => controller.abort(), this.timeoutMs);

    try {
      const response = await fetch(url, {
        method,
        headers: {
          'api-key': this.apiKey,
          'Content-Type': 'application/json',
        },
        body: jsonBody !== undefined ? JSON.stringify(jsonBody) : undefined,
        signal: controller.signal,
      });
      const body = await response.text();
      return { status: response.status, body };
    } catch (err) {
      throw new NetworkError(err instanceof Error ? err.message : String(err));
    } finally {
      clearTimeout(timer);
    }
  }
}
