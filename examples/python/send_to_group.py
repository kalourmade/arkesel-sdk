import os
import sys

from kalourmade_arkesel import ArkeselClient

group_name = sys.argv[1] if len(sys.argv) > 1 else sys.exit("Usage: python send_to_group.py <group_name>")
client = ArkeselClient(os.environ["ARKESEL_API_KEY"])

client.sms.send_to_group(sender="Arkesel", group_name=group_name, message="Hello group, from the Arkesel Python SDK")

print(f"Sent to group '{group_name}'")
