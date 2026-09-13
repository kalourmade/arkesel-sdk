import { ArkeselClient } from '@kalourmade/arkesel-sms';

const groupName = process.argv[2];
if (!groupName) {
  console.error('Usage: tsx sendToGroup.ts <group_name>');
  process.exit(1);
}

const client = new ArkeselClient(process.env.ARKESEL_API_KEY!);
await client.sms.sendToGroup({ sender: 'Arkesel', groupName, message: 'Hello group, from the Arkesel Node SDK' });

console.log(`Sent to group '${groupName}'`);
