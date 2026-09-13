import { describe, expect, it } from 'vitest';
import { BalanceResource } from '../src/balance';
import { FakeTransport } from './fakes';

describe('BalanceResource', () => {
  it('get fetches balance details', async () => {
    const body = JSON.stringify({ status: 'success', data: { sms_balance: '2003', main_balance: 'GHS 20.99' } });
    const transport = new FakeTransport([{ status: 200, body }]);
    const resource = new BalanceResource(transport);

    const balance = await resource.get();

    expect(balance.smsBalance).toBe('2003');
    expect(transport.requests[0].method).toBe('GET');
    expect(transport.requests[0].path).toBe('/api/v2/clients/balance-details');
  });
});
