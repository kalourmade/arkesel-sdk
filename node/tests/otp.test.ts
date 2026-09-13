import { describe, expect, it } from 'vitest';
import { OtpResource } from '../src/otp';
import { ValidationError } from '../src/errors';
import { FakeTransport } from './fakes';

describe('OtpResource', () => {
  it('generate posts snake_case body and returns a parsed result', async () => {
    const body = JSON.stringify({ code: '1000', ussd_code: '*928*01#', message: 'Successful, OTP is being processed for delivery' });
    const transport = new FakeTransport([{ status: 200, body }]);
    const resource = new OtpResource(transport);

    const result = await resource.generate({
      expiry: 5,
      length: 6,
      medium: 'sms',
      message: 'This is OTP from Arkesel, %otp_code%',
      number: '233544919953',
      senderId: 'Arkesel',
      type: 'numeric',
    });

    expect(result.code).toBe('1000');
    expect(result.ussdCode).toBe('*928*01#');
    expect(transport.requests[0].path).toBe('/api/otp/generate');
    expect(transport.requests[0].jsonBody).toEqual({
      expiry: 5,
      length: 6,
      medium: 'sms',
      message: 'This is OTP from Arkesel, %otp_code%',
      number: '233544919953',
      sender_id: 'Arkesel',
      type: 'numeric',
    });
  });

  it('generate result has a null ussdCode when absent', async () => {
    const body = JSON.stringify({ code: '1000', message: 'Successful' });
    const transport = new FakeTransport([{ status: 200, body }]);
    const resource = new OtpResource(transport);

    const result = await resource.generate({
      expiry: 5, length: 6, medium: 'sms', message: 'msg %otp_code%',
      number: '233544919953', senderId: 'Arkesel', type: 'numeric',
    });

    expect(result.ussdCode).toBeNull();
  });

  it('verify posts code and number', async () => {
    const body = JSON.stringify({ code: '1100', message: 'Successful' });
    const transport = new FakeTransport([{ status: 200, body }]);
    const resource = new OtpResource(transport);

    const result = await resource.verify({ code: '173882', number: '233544919953' });

    expect(result.code).toBe('1100');
    expect(transport.requests[0].path).toBe('/api/otp/verify');
    expect(transport.requests[0].jsonBody).toEqual({ code: '173882', number: '233544919953' });
  });

  it('raises the mapped exception for error statuses', async () => {
    const transport = new FakeTransport([{ status: 422, body: '{}' }]);
    const resource = new OtpResource(transport);

    await expect(resource.verify({ code: '', number: '233544919953' })).rejects.toBeInstanceOf(ValidationError);
  });
});
