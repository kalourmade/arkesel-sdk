import os
import sys

from kalourmade_arkesel import ArkeselClient

number = sys.argv[1] if len(sys.argv) > 1 else sys.exit("Usage: python otp_generate.py <number>")
client = ArkeselClient(os.environ["ARKESEL_API_KEY"])

result = client.otp.generate(
    expiry=5, length=6, medium="sms",
    message="Your Arkesel Python SDK test code is %otp_code%",
    number=number, sender_id="Arkesel", type="numeric",
)

print(f"Generated: {result.message}")
