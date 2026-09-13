# kalourmade-arkesel

Python SDK for the [Arkesel](https://arkesel.com) SMS/OTP gateway.

## Install

```bash
pip install kalourmade-arkesel
```

Requires Python 3.9+. Single dependency: `httpx`.

## Quickstart

Two things this SDK does: send SMS messages, and generate/verify OTPs.

```python
import os
from kalourmade_arkesel import ArkeselClient

client = ArkeselClient(os.environ["ARKESEL_API_KEY"])

# Send a message
response = client.sms.send(sender="Arkesel", recipients=["233544919953"], message="Hello from Arkesel")

for result in response.results:
    print(f"{result.recipient} -> {result.id}")

# Generate and verify an OTP
client.otp.generate(
    expiry=5, length=6, medium="sms",
    message="Your code is %otp_code%",
    number="233544919953", sender_id="Arkesel", type="numeric",
)

client.otp.verify(code=user_entered_code, number="233544919953")
```

Full documentation, runnable examples, and the PHP/Node SDKs:
[github.com/kalourmade/arkesel-sdk](https://github.com/kalourmade/arkesel-sdk).
