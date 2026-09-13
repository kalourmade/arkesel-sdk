import os
import sys

from kalourmade_arkesel import ArkeselClient

if len(sys.argv) < 3:
    sys.exit("Usage: python otp_verify.py <number> <code>")
number, code = sys.argv[1], sys.argv[2]
client = ArkeselClient(os.environ["ARKESEL_API_KEY"])

result = client.otp.verify(code=code, number=number)

print(f"Verified: {result.message}")
