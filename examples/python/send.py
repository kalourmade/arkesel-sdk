import os

from kalourmade_arkesel import ArkeselClient

client = ArkeselClient(os.environ["ARKESEL_API_KEY"])

response = client.sms.send(sender="Arkesel", recipients=["233544919953"], message="Hello from the Arkesel Python SDK")

for result in response.results:
    print(f"Sent to {result.recipient} — id={result.id}")
for invalid in response.invalid_numbers:
    print(f"Invalid number: {invalid}")
