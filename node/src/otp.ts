import type { Transport } from './http.js';
import { throwIfError } from './errorMapping.js';
import {
  OtpGenerateResult,
  OtpVerifyResult,
  otpGenerateResultFromJson,
  otpVerifyResultFromJson,
} from './models.js';

export interface GenerateOtpParams {
  expiry: number;
  length: number;
  medium: 'sms' | 'voice';
  message: string;
  number: string;
  senderId: string;
  type: 'numeric' | 'alphanumeric';
}

export class OtpResource {
  constructor(private readonly transport: Transport) {}

  async generate(params: GenerateOtpParams): Promise<OtpGenerateResult> {
    const { status, body } = await this.transport.request('POST', '/api/otp/generate', undefined, {
      expiry: params.expiry,
      length: params.length,
      medium: params.medium,
      message: params.message,
      number: params.number,
      sender_id: params.senderId,
      type: params.type,
    });
    throwIfError(status, body);
    return otpGenerateResultFromJson(JSON.parse(body));
  }

  async verify(params: { code: string; number: string }): Promise<OtpVerifyResult> {
    const { status, body } = await this.transport.request('POST', '/api/otp/verify', undefined, {
      code: params.code,
      number: params.number,
    });
    throwIfError(status, body);
    return otpVerifyResultFromJson(JSON.parse(body));
  }
}
