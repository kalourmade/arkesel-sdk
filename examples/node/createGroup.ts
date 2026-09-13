import { ArkeselClient } from '@kalourmade/arkesel-sms';

const groupName = process.argv[2];
if (!groupName) {
  console.error('Usage: tsx createGroup.ts <group_name>');
  process.exit(1);
}

const client = new ArkeselClient(process.env.ARKESEL_API_KEY!);

await client.groups.create(groupName);
await client.groups.addContacts(groupName, [
  { phoneNumber: '233544919953', firstName: 'Arkesel', lastName: 'Dev' },
]);

console.log(`Created group '${groupName}' with 1 contact`);
