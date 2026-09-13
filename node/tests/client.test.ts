import { describe, expect, it } from 'vitest';
import { ArkeselClient } from '../src/client';
import { SmsResource } from '../src/sms';
import { BalanceResource } from '../src/balance';
import { GroupsResource } from '../src/groups';
import { OtpResource } from '../src/otp';

describe('ArkeselClient', () => {
  it('exposes all four resources', () => {
    const client = new ArkeselClient('arkesel_test_key');

    expect(client.sms).toBeInstanceOf(SmsResource);
    expect(client.balance).toBeInstanceOf(BalanceResource);
    expect(client.groups).toBeInstanceOf(GroupsResource);
    expect(client.otp).toBeInstanceOf(OtpResource);
  });
});
