# Arkesel Node SDK

`@kalourmade/arkesel-sms` — Node/TypeScript SDK for the Arkesel SMS/OTP API.

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

Full runnable examples: [`examples/node/send.ts`](../examples/node/send.ts),
[`get.ts`](../examples/node/get.ts), [`messageReports.ts`](../examples/node/messageReports.ts),
[`createGroup.ts`](../examples/node/createGroup.ts), [`sendToGroup.ts`](../examples/node/sendToGroup.ts),
[`otpGenerate.ts`](../examples/node/otpGenerate.ts), [`otpVerify.ts`](../examples/node/otpVerify.ts).

To run them against your local checkout of the SDK (rather than a
published version), link the package in two steps — `npm link` alone
registers it globally but doesn't make it resolvable from a sibling
directory:

```bash
cd node && npm install && npm run build && npm link
cd ../examples/node && npm link @kalourmade/arkesel-sms
ARKESEL_API_KEY=your_key npx tsx send.ts
```

## Error handling

```typescript
import {
  AuthenticationError, InsufficientBalanceError, ValidationError, ApiError, NetworkError,
} from '@kalourmade/arkesel-sms';

try {
  await client.sms.send({ sender: 'Arkesel', recipients: ['233544919953'], message: 'Hi' });
} catch (e) {
  if (e instanceof AuthenticationError) { /* bad/missing api-key, or inactive gateway */ }
  else if (e instanceof InsufficientBalanceError) { /* top up your Arkesel balance */ }
  else if (e instanceof ValidationError) { /* bad request payload */ }
  else if (e instanceof ApiError) { /* e.statusCode, e.rawBody */ }
  else if (e instanceof NetworkError) { /* couldn't reach Arkesel */ }
}
```

## Parsing delivery callbacks

Arkesel has no signature scheme for its delivery callback — this
parser validates shape only, it does not authenticate the request:

```typescript
import { parseDeliveryCallback } from '@kalourmade/arkesel-sms';

const result = parseDeliveryCallback(req.query);
// result.smsId, result.status
```

Full example: [`examples/node/parseDeliveryCallback.ts`](../examples/node/parseDeliveryCallback.ts).
