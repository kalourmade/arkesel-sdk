# kalourmade-arkesel

Python SDK for the [Arkesel](https://arkesel.com) SMS/OTP gateway.

## Install

```bash
pip install kalourmade-arkesel
```

Requires Python 3.9+. Single dependency: `httpx`.

## Quickstart

```python
import os
from kalourmade_arkesel import ArkeselClient

client = ArkeselClient(os.environ["ARKESEL_API_KEY"])

response = client.sms.send(sender="Arkesel", recipients=["233544919953"], message="Hello from Arkesel")

for result in response.results:
    print(f"{result.recipient} -> {result.id}")
```

Full documentation, runnable examples, and the PHP/Node SDKs:
[github.com/kalourmade/arkesel-sdk](https://github.com/kalourmade/arkesel-sdk).
