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

Two things this SDK does: send SMS messages, and generate/verify OTPs.

```typescript
import { ArkeselClient } from '@kalourmade/arkesel-sms';

const client = new ArkeselClient(process.env.ARKESEL_API_KEY!);

// Send a message
const response = await client.sms.send({
  sender: 'Arkesel',
  recipients: ['233544919953'],
  message: 'Hello from Arkesel',
});

// Generate and verify an OTP
await client.otp.generate({
  expiry: 5, length: 6, medium: 'sms',
  message: 'Your code is %otp_code%',
  number: '233544919953', senderId: 'Arkesel', type: 'numeric',
});

await client.otp.verify({ code: userEnteredCode, number: '233544919953' });
```

Full documentation, runnable examples, and the PHP/Python SDKs:
[github.com/kalourmade/arkesel-sdk](https://github.com/kalourmade/arkesel-sdk).
