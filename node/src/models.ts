export type DeliveryStatus = 'DELIVERED' | 'SUBMITTED' | 'PROHIBITED' | 'QUEUED' | 'NOT_DELIVERED' | 'EXPIRED';

export interface SmsDetails {
  id: string;
  status: DeliveryStatus;
  sender: string;
  recipient: string;
  message: string;
  messageCount: number;
  sentAtTime: string;
}

// eslint-disable-next-line @typescript-eslint/no-explicit-any
export function smsDetailsFromJson(data: any): SmsDetails {
  return {
    id: data.ID,
    status: data.status,
    sender: data.sender,
    recipient: data.recipient,
    message: data.message,
    messageCount: data.message_count,
    sentAtTime: data.sent_at_time,
  };
}

export interface SendResultEntry {
  recipient: string;
  id: string;
}

export interface SendResponse {
  results: SendResultEntry[];
  invalidNumbers: string[];
  message: string | null;
}

// eslint-disable-next-line @typescript-eslint/no-explicit-any
export function sendResponseFromJson(data: any): SendResponse {
  const results: SendResultEntry[] = [];
  const invalidNumbers: string[] = [];

  // eslint-disable-next-line @typescript-eslint/no-explicit-any
  for (const entry of (data.data ?? []) as any[]) {
    if (entry.recipient !== undefined && entry.id !== undefined) {
      results.push({ recipient: entry.recipient, id: entry.id });
    } else if (entry['invalid numbers'] !== undefined) {
      invalidNumbers.push(...entry['invalid numbers']);
    }
  }

  return { results, invalidNumbers, message: data.message ?? null };
}

export interface ReportEntry {
  status: DeliveryStatus | null;
  sender: string | null;
  recipient: string | null;
  message: string | null;
  messageCount: number | null;
  sentAtTime: string | null;
  error: string | null;
}

// eslint-disable-next-line @typescript-eslint/no-explicit-any
export function reportEntryFromJson(data: any): ReportEntry {
  if (data.status === 'error') {
    return {
      status: null,
      sender: null,
      recipient: null,
      message: null,
      messageCount: null,
      sentAtTime: null,
      error: data.response,
    };
  }

  return {
    status: data.status,
    sender: data.sender,
    recipient: data.recipient,
    message: data.message,
    messageCount: data.message_count,
    sentAtTime: data.sent_at_time,
    error: null,
  };
}

export interface Balance {
  smsBalance: string;
  mainBalance: string;
}

// eslint-disable-next-line @typescript-eslint/no-explicit-any
export function balanceFromJson(data: any): Balance {
  return { smsBalance: data.sms_balance, mainBalance: data.main_balance };
}

export interface OtpGenerateResult {
  code: string;
  message: string;
  ussdCode: string | null;
}

// eslint-disable-next-line @typescript-eslint/no-explicit-any
export function otpGenerateResultFromJson(data: any): OtpGenerateResult {
  return { code: data.code, message: data.message, ussdCode: data.ussd_code ?? null };
}

export interface OtpVerifyResult {
  code: string;
  message: string;
}

// eslint-disable-next-line @typescript-eslint/no-explicit-any
export function otpVerifyResultFromJson(data: any): OtpVerifyResult {
  return { code: data.code, message: data.message };
}
