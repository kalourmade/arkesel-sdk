import os
import sys

from kalourmade_arkesel import ArkeselClient

group_name = sys.argv[1] if len(sys.argv) > 1 else sys.exit("Usage: python create_group.py <group_name>")
client = ArkeselClient(os.environ["ARKESEL_API_KEY"])

client.groups.create(group_name)
client.groups.add_contacts(group_name, [
    {"phone_number": "233544919953", "first_name": "Arkesel", "last_name": "Dev"},
])

print(f"Created group '{group_name}' with 1 contact")
