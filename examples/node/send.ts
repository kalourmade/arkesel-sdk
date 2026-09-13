import { ArkeselClient } from '@kalourmade/arkesel-sms';

const client = new ArkeselClient(process.env.ARKESEL_API_KEY!);

const response = await client.sms.send({
  sender: 'Arkesel',
  recipients: ['233544919953'],
  message: 'Hello from the Arkesel Node SDK',
});

for (const result of response.results) {
  console.log(`Sent to ${result.recipient} — id=${result.id}`);
}
for (const invalid of response.invalidNumbers) {
  console.log(`Invalid number: ${invalid}`);
}
