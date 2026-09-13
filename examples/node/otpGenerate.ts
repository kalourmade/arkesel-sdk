import { ArkeselClient } from '@kalourmade/arkesel-sms';

const number = process.argv[2];
if (!number) {
  console.error('Usage: tsx otpGenerate.ts <number>');
  process.exit(1);
}

const client = new ArkeselClient(process.env.ARKESEL_API_KEY!);
const result = await client.otp.generate({
  expiry: 5,
  length: 6,
  medium: 'sms',
  message: 'Your Arkesel Node SDK test code is %otp_code%',
  number,
  senderId: 'Arkesel',
  type: 'numeric',
});

console.log(`Generated: ${result.message}`);
