import type { HttpResponse, Transport } from '../src/http';

export class FakeTransport implements Transport {
  public requests: Array<{
    method: string;
    path: string;
    query?: Record<string, string | number>;
    jsonBody?: unknown;
  }> = [];

  constructor(private responses: HttpResponse[]) {}

  async request(
    method: string,
    path: string,
    query?: Record<string, string | number>,
    jsonBody?: unknown
  ): Promise<HttpResponse> {
    this.requests.push({ method, path, query, jsonBody });
    const response = this.responses.shift();
    if (!response) {
      throw new Error('FakeTransport: no more queued responses');
    }
    return response;
  }
}
