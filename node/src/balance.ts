import type { Transport } from './http.js';
import { throwIfError } from './errorMapping.js';
import { Balance, balanceFromJson } from './models.js';

export class BalanceResource {
  constructor(private readonly transport: Transport) {}

  async get(): Promise<Balance> {
    const { status, body } = await this.transport.request('GET', '/api/v2/clients/balance-details');
    throwIfError(status, body);
    return balanceFromJson(JSON.parse(body).data);
  }
}
