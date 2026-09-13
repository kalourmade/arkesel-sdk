import { ValidationError } from './errors.js';

export function parseDeliveryCallback(
  query: Record<string, string | undefined>
): { smsId: string; status: string } {
  if (!query.sms_id || !query.status) {
    throw new ValidationError('Missing sms_id or status query parameter');
  }

  return { smsId: query.sms_id, status: query.status };
}
