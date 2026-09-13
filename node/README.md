# @kalourmade/arkesel-sms

Node/TypeScript SDK for the [Arkesel](https://arkesel.com) SMS/OTP gateway.

## Install

```bash
npm install @kalourmade/arkesel-sms
# or
pnpm add @kalourmade/arkesel-sms
```

Requires Node 18+. No runtime dependencies. Ships ESM + CJS + types.

## Quickstart

```typescript
import { ArkeselClient } from '@kalourmade/arkesel-sms';

const client = new ArkeselClient(process.env.ARKESEL_API_KEY!);

const response = await client.sms.send({
  sender: 'Arkesel',
  recipients: ['233544919953'],
  message: 'Hello from Arkesel',
});
```

Full documentation, runnable examples, and the PHP/Python SDKs:
[github.com/kalourmade/arkesel-sdk](https://github.com/kalourmade/arkesel-sdk).
