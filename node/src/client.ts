import { BalanceResource } from './balance.js';
import { FetchTransport } from './http.js';
import { GroupsResource } from './groups.js';
import { OtpResource } from './otp.js';
import { SmsResource } from './sms.js';

export class ArkeselClient {
  readonly sms: SmsResource;
  readonly balance: BalanceResource;
  readonly groups: GroupsResource;
  readonly otp: OtpResource;

  constructor(
    apiKey: string,
    baseUrl: string = 'https://sms.arkesel.com',
    timeoutMs: number = 30000
  ) {
    const transport = new FetchTransport(apiKey, baseUrl, timeoutMs);
    this.sms = new SmsResource(transport);
    this.balance = new BalanceResource(transport);
    this.groups = new GroupsResource(transport);
    this.otp = new OtpResource(transport);
  }
}
