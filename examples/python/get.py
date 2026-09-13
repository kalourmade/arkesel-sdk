import os
import sys

from kalourmade_arkesel import ArkeselClient

id = sys.argv[1] if len(sys.argv) > 1 else sys.exit("Usage: python get.py <message_id>")
client = ArkeselClient(os.environ["ARKESEL_API_KEY"])

details = client.sms.get(id)

print(f"{details.id} — {details.message} ({details.status.value})")
