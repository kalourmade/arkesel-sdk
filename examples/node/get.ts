import { ArkeselClient } from '@kalourmade/arkesel-sms';

const id = process.argv[2];
if (!id) {
  console.error('Usage: tsx get.ts <message_id>');
  process.exit(1);
}

const client = new ArkeselClient(process.env.ARKESEL_API_KEY!);
const details = await client.sms.get(id);

console.log(`${details.id} — ${details.message} (${details.status})`);
