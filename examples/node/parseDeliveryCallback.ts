import { parseDeliveryCallback, ValidationError } from '@kalourmade/arkesel-sms';

// Simulate an incoming GET callback for demonstration purposes.
const query = { sms_id: 'abc-123', status: 'DELIVERED' };

const result = parseDeliveryCallback(query);
console.log(`sms_id=${result.smsId} status=${result.status}`);

// Demonstrate the failure case: a malformed callback is rejected.
try {
  parseDeliveryCallback({ sms_id: 'abc-123' });
} catch (e) {
  if (e instanceof ValidationError) {
    console.log(`Correctly rejected malformed callback: ${e.message}`);
  }
}
