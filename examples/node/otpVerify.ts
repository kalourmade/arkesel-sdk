import { ArkeselClient } from '@kalourmade/arkesel-sms';

const [number, code] = process.argv.slice(2);
if (!number || !code) {
  console.error('Usage: tsx otpVerify.ts <number> <code>');
  process.exit(1);
}

const client = new ArkeselClient(process.env.ARKESEL_API_KEY!);
const result = await client.otp.verify({ code, number });

console.log(`Verified: ${result.message}`);
