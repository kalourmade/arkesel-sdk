import { ArkeselClient } from '@kalourmade/arkesel-sms';

const ids = process.argv.slice(2);
if (ids.length === 0) {
  console.error('Usage: tsx messageReports.ts <id> [id...]');
  process.exit(1);
}

const client = new ArkeselClient(process.env.ARKESEL_API_KEY!);
const reports = await client.sms.messageReports(ids);

for (const [id, report] of Object.entries(reports)) {
  if (report.error !== null) {
    console.log(`${id} — error: ${report.error}`);
  } else {
    console.log(`${id} — ${report.message} (${report.status})`);
  }
}
