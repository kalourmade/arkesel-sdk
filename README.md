# Arkesel SDKs

Client SDKs for the [Arkesel](https://arkesel.com) SMS/OTP gateway —
PHP, Python, and Node/TypeScript, sharing one design across all three.

| Language | Package | Registry | Docs |
|---|---|---|---|
| PHP | `kalourmade/arkesel-sms` | [Packagist](https://packagist.org/packages/kalourmade/arkesel-sms) | [docs/php.md](docs/php.md) |
| Python | `kalourmade-arkesel` | [PyPI](https://pypi.org/project/kalourmade-arkesel/) | [docs/python.md](docs/python.md) |
| Node | `@kalourmade/arkesel-sms` | [npm](https://www.npmjs.com/package/@kalourmade/arkesel-sms) | [docs/node.md](docs/node.md) |

## Install

```bash
composer require kalourmade/arkesel-sms   # PHP, 8.1+
pip install kalourmade-arkesel            # Python, 3.9+
npm install @kalourmade/arkesel-sms       # Node, 18+ (or pnpm add)
```

All three ship with minimal dependencies (PHP: none, Python: `httpx`
only, Node: none) and read your API key from the environment —
`getenv('ARKESEL_API_KEY')` / `os.environ["ARKESEL_API_KEY"]` /
`process.env.ARKESEL_API_KEY`, never hardcoded.

## Quickstart

This is one SDK, two jobs: **send SMS messages**, and **generate/verify
OTPs**. Both are first-class — a `client.sms` resource and a
`client.otp` resource on the exact same client, not a messaging SDK
with OTP bolted on.

### Sending a message

**PHP**
```php
use Kalourmade\Arkesel\ArkeselClient;

$client = new ArkeselClient(getenv('ARKESEL_API_KEY'));

$response = $client->sms->send('Arkesel', ['233544919953'], 'Hello from Arkesel');

foreach ($response->results as $result) {
    echo "{$result->recipient} -> {$result->id}\n";
}
```

**Python**
```python
import os
from kalourmade_arkesel import ArkeselClient

client = ArkeselClient(os.environ["ARKESEL_API_KEY"])

response = client.sms.send(sender="Arkesel", recipients=["233544919953"], message="Hello from Arkesel")

for result in response.results:
    print(f"{result.recipient} -> {result.id}")
```

**Node**
```typescript
import { ArkeselClient } from '@kalourmade/arkesel-sms';

const client = new ArkeselClient(process.env.ARKESEL_API_KEY!);

const response = await client.sms.send({
  sender: 'Arkesel',
  recipients: ['233544919953'],
  message: 'Hello from Arkesel',
});
```

### Generating and verifying an OTP

**PHP**
```php
$otp = $client->otp->generate(
    expiry: 5, length: 6, medium: 'sms',
    message: 'Your code is %otp_code%',
    number: '233544919953', senderId: 'Arkesel', type: 'numeric'
);

// ...later, once the user enters the code they received:
$client->otp->verify(code: $userEnteredCode, number: '233544919953');
```

**Python**
```python
client.otp.generate(
    expiry=5, length=6, medium="sms",
    message="Your code is %otp_code%",
    number="233544919953", sender_id="Arkesel", type="numeric",
)

# ...later, once the user enters the code they received:
client.otp.verify(code=user_entered_code, number="233544919953")
```

**Node**
```typescript
await client.otp.generate({
  expiry: 5, length: 6, medium: 'sms',
  message: 'Your code is %otp_code%',
  number: '233544919953', senderId: 'Arkesel', type: 'numeric',
});

// ...later, once the user enters the code they received:
await client.otp.verify({ code: userEnteredCode, number: '233544919953' });
```

## What each SDK covers

All three expose the same four resources on the client — `sms`,
`groups`, `balance`, `otp` — plus a standalone delivery-callback
parser. Method names follow each language's own convention (PHP/Node:
camelCase, Python: snake_case), but map onto the same underlying
Arkesel endpoints.

| Resource | Methods | What it does |
|---|---|---|
| `sms` | `send`, `get`, `messageReports`/`message_reports`, `sendToGroup`/`send_to_group` | Send SMS (batch recipients, scheduling, webhooks), check delivery status by ID, bulk status lookup, send to a saved contact group |
| `groups` | `create`, `addContacts`/`add_contacts` | Create a contact group, add contacts to it |
| `balance` | `get` | Check SMS and account balance |
| `otp` | `generate`, `verify` | Generate a one-time password (SMS or voice), verify a code a user entered |
| *(standalone)* | `parseDeliveryCallback`/`parse_delivery_callback`/`DeliveryCallback::parse` | Parse the `sms_id`/`status` query params Arkesel sends to your `callback_url` |

Every method raises a typed exception on failure —
`AuthenticationError`, `InsufficientBalanceError`, `ValidationError`,
`ApiError` (carries the HTTP status + raw response body), or
`NetworkError` — see each language's docs page for the exact
catch-block shape.

Arkesel has **no signature scheme** for its delivery callback (a plain
GET with two query params, nothing cryptographic) — the parser
validates shape only. It's not a security check, just a convenience.

## Examples

Every example script reads `ARKESEL_API_KEY` from the environment
(and a phone number/group name from its CLI arguments where relevant)
and hits the real API — no mocking, so you'll need a valid key to run
them.

| What it does | PHP | Python | Node |
|---|---|---|---|
| Send an SMS | [`send.php`](examples/php/send.php) | [`send.py`](examples/python/send.py) | [`send.ts`](examples/node/send.ts) |
| Get a message's status | [`get.php`](examples/php/get.php) | [`get.py`](examples/python/get.py) | [`get.ts`](examples/node/get.ts) |
| Bulk status lookup | [`message_reports.php`](examples/php/message_reports.php) | [`message_reports.py`](examples/python/message_reports.py) | [`messageReports.ts`](examples/node/messageReports.ts) |
| Create a contact group | [`create_group.php`](examples/php/create_group.php) | [`create_group.py`](examples/python/create_group.py) | [`createGroup.ts`](examples/node/createGroup.ts) |
| Send to a contact group | [`send_to_group.php`](examples/php/send_to_group.php) | [`send_to_group.py`](examples/python/send_to_group.py) | [`sendToGroup.ts`](examples/node/sendToGroup.ts) |
| Generate an OTP | [`otp_generate.php`](examples/php/otp_generate.php) | [`otp_generate.py`](examples/python/otp_generate.py) | [`otpGenerate.ts`](examples/node/otpGenerate.ts) |
| Verify an OTP | [`otp_verify.php`](examples/php/otp_verify.php) | [`otp_verify.py`](examples/python/otp_verify.py) | [`otpVerify.ts`](examples/node/otpVerify.ts) |
| Parse a delivery callback | [`parse_delivery_callback.php`](examples/php/parse_delivery_callback.php) | [`parse_delivery_callback.py`](examples/python/parse_delivery_callback.py) | [`parseDeliveryCallback.ts`](examples/node/parseDeliveryCallback.ts) |

Running them:

```bash
# PHP — from the repo root, after `composer install`
ARKESEL_API_KEY=your_key php examples/php/send.php

# Python — from python/, after `pip install -e ".[dev]"` (with that venv active)
ARKESEL_API_KEY=your_key python ../examples/python/send.py

# Node — build + link the package, then link it into examples/node:
#   cd node && npm install && npm run build && npm link
#   cd ../examples/node && npm link @kalourmade/arkesel-sms
ARKESEL_API_KEY=your_key npx tsx send.ts   # from examples/node/
```

## Status

v1 covers SMS, Contact Groups, Balance, and OTP. See
[docs/ROADMAP.md](docs/ROADMAP.md) for what's planned next (Voice SMS
and a browser "click to record" demo).

## Releasing

All three packages are released together from a single tag. Pushing a
tag like `v0.1.0` triggers `.github/workflows/release.yml`, which
reads the version from the tag itself and publishes all three
packages at that version in one run:

```bash
git tag v0.1.0
git push origin v0.1.0
```
