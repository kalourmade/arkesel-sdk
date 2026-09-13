# Arkesel Python SDK

`kalourmade-arkesel` — Python SDK for the Arkesel SMS/OTP API.

## Install

```bash
pip install kalourmade-arkesel
```

Requires Python 3.9+. Single dependency: `httpx`.

## Quickstart

Two things this SDK does: send SMS messages, and generate/verify OTPs.
Both are first-class resources on the same client.

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

Full runnable examples: [`examples/python/send.py`](../examples/python/send.py),
[`get.py`](../examples/python/get.py), [`message_reports.py`](../examples/python/message_reports.py),
[`create_group.py`](../examples/python/create_group.py), [`send_to_group.py`](../examples/python/send_to_group.py),
[`otp_generate.py`](../examples/python/otp_generate.py), [`otp_verify.py`](../examples/python/otp_verify.py).

## Error handling

```python
from kalourmade_arkesel.exceptions import (
    AuthenticationError, InsufficientBalanceError, ValidationError, ApiError, NetworkError,
)

try:
    client.sms.send(sender="Arkesel", recipients=["233544919953"], message="Hi")
except AuthenticationError:
    ...  # bad/missing api-key, or inactive gateway
except InsufficientBalanceError:
    ...  # top up your Arkesel balance
except ValidationError:
    ...  # bad request payload
except ApiError as e:
    ...  # other API error — e.status_code, e.raw_body
except NetworkError:
    ...  # couldn't reach Arkesel
```

## Parsing delivery callbacks

Arkesel has no signature scheme for its delivery callback — this
parser validates shape only, it does not authenticate the request:

```python
from kalourmade_arkesel import parse_delivery_callback

result = parse_delivery_callback(request.args)
# result["sms_id"], result["status"]
```

Full example: [`examples/python/parse_delivery_callback.py`](../examples/python/parse_delivery_callback.py).
