import type { Transport } from './http.js';
import { throwIfError } from './errorMapping.js';
import {
  ReportEntry,
  SendResponse,
  SmsDetails,
  reportEntryFromJson,
  sendResponseFromJson,
  smsDetailsFromJson,
} from './models.js';

export interface SendParams {
  sender: string;
  recipients: string[];
  message: string;
  callbackUrl?: string;
  scheduledDate?: string;
  useCase?: string;
  sandbox?: boolean;
}

export class SmsResource {
  constructor(private readonly transport: Transport) {}

  async send(params: SendParams): Promise<SendResponse> {
    const body: Record<string, unknown> = {
      sender: params.sender,
      recipients: params.recipients,
      message: params.message,
    };
    if (params.callbackUrl !== undefined) body.callback_url = params.callbackUrl;
    if (params.scheduledDate !== undefined) body.scheduled_date = params.scheduledDate;
    if (params.useCase !== undefined) body.use_case = params.useCase;
    if (params.sandbox !== undefined) body.sandbox = params.sandbox;

    const { status, body: respBody } = await this.transport.request('POST', '/api/v2/sms/send', undefined, body);
    throwIfError(status, respBody);
    return sendResponseFromJson(JSON.parse(respBody));
  }

  async get(id: string): Promise<SmsDetails> {
    const { status, body } = await this.transport.request('GET', `/api/v2/sms/${id}`);
    throwIfError(status, body);
    return smsDetailsFromJson(JSON.parse(body).data);
  }

  async messageReports(ids: string[]): Promise<Record<string, ReportEntry>> {
    const { status, body } = await this.transport.request(
      'POST',
      '/api/v2/sms/message-reports',
      undefined,
      { msg_ids: ids }
    );
    throwIfError(status, body);

    const data = JSON.parse(body).data as Record<string, unknown>;
    const reports: Record<string, ReportEntry> = {};
    for (const [id, entry] of Object.entries(data)) {
      reports[id] = reportEntryFromJson(entry);
    }
    return reports;
  }

  async sendToGroup(params: { sender: string; groupName: string; message: string }): Promise<void> {
    const { status, body } = await this.transport.request(
      'POST',
      '/api/v2/sms/send/contact-group',
      undefined,
      { sender: params.sender, group_name: params.groupName, message: params.message }
    );
    throwIfError(status, body);
  }
}
