import { describe, expect, it } from 'vitest';
import {
  balanceFromJson,
  otpGenerateResultFromJson,
  reportEntryFromJson,
  sendResponseFromJson,
  smsDetailsFromJson,
} from '../src/models';

describe('smsDetailsFromJson', () => {
  it('maps the uppercase ID field', () => {
    const details = smsDetailsFromJson({
      ID: 'f3be70c1-3545-4677-b607-6b5f32202652',
      status: 'DELIVERED',
      sender: 'Arkesel',
      recipient: '233544919953',
      message: 'Welcome to version 2 of our API!',
      message_count: 1,
      sent_at_time: '2021-04-09 18:44:05',
    });

    expect(details.id).toBe('f3be70c1-3545-4677-b607-6b5f32202652');
    expect(details.status).toBe('DELIVERED');
    expect(details.messageCount).toBe(1);
  });
});

describe('sendResponseFromJson', () => {
  it('separates results and invalid numbers', () => {
    const response = sendResponseFromJson({
      status: 'success',
      data: [
        { recipient: '233544919953', id: '9b752841-7ee7-4d40-b4fe-768bfb1da4f0' },
        { recipient: '233544919953', id: '7ea01acd-485c-4df3-b646-e9e24430e145' },
        { 'invalid numbers': ['22354674948'] },
      ],
    });

    expect(response.results).toHaveLength(2);
    expect(response.results[0].id).toBe('9b752841-7ee7-4d40-b4fe-768bfb1da4f0');
    expect(response.invalidNumbers).toEqual(['22354674948']);
    expect(response.message).toBeNull();
  });

  it('handles a scheduled send with no data key', () => {
    const response = sendResponseFromJson({ status: 'success', message: 'SMS request sent successfully!' });

    expect(response.results).toEqual([]);
    expect(response.invalidNumbers).toEqual([]);
    expect(response.message).toBe('SMS request sent successfully!');
  });
});

describe('reportEntryFromJson', () => {
  it('maps a successful report', () => {
    const entry = reportEntryFromJson({
      sender: 'Arkesel',
      recipient: '233540000000',
      status: 'DELIVERED',
      message: 'Welcome to version 2 of our API!',
      message_count: 1,
      sent_at_time: '2021-04-09 18:44:05',
    });

    expect(entry.status).toBe('DELIVERED');
    expect(entry.error).toBeNull();
  });

  it('maps an error stub', () => {
    const entry = reportEntryFromJson({ status: 'error', response: 'message does not exist' });

    expect(entry.status).toBeNull();
    expect(entry.error).toBe('message does not exist');
  });
});

describe('balanceFromJson', () => {
  it('maps fields', () => {
    const balance = balanceFromJson({ sms_balance: '2003', main_balance: 'GHS 20.99' });

    expect(balance.smsBalance).toBe('2003');
    expect(balance.mainBalance).toBe('GHS 20.99');
  });
});

describe('otpGenerateResultFromJson', () => {
  it('handles optional ussd_code', () => {
    const withUssd = otpGenerateResultFromJson({ code: '1000', ussd_code: '*928*01#', message: 'Successful' });
    const withoutUssd = otpGenerateResultFromJson({ code: '1000', message: 'Successful' });

    expect(withUssd.ussdCode).toBe('*928*01#');
    expect(withoutUssd.ussdCode).toBeNull();
  });
});
