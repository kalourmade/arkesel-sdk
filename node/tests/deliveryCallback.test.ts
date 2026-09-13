import { describe, expect, it } from 'vitest';
import { parseDeliveryCallback } from '../src/deliveryCallback';
import { ValidationError } from '../src/errors';

describe('parseDeliveryCallback', () => {
  it('returns smsId and status', () => {
    const result = parseDeliveryCallback({ sms_id: 'abc-123', status: 'DELIVERED' });

    expect(result.smsId).toBe('abc-123');
    expect(result.status).toBe('DELIVERED');
  });

  it('throws when sms_id is missing', () => {
    expect(() => parseDeliveryCallback({ status: 'DELIVERED' })).toThrow(ValidationError);
  });

  it('throws when status is missing', () => {
    expect(() => parseDeliveryCallback({ sms_id: 'abc-123' })).toThrow(ValidationError);
  });
});
