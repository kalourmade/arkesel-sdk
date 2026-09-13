from kalourmade_arkesel import parse_delivery_callback
from kalourmade_arkesel.exceptions import ValidationError

# Simulate an incoming GET callback for demonstration purposes.
query = {"sms_id": "abc-123", "status": "DELIVERED"}

result = parse_delivery_callback(query)
print(f"sms_id={result['sms_id']} status={result['status']}")

# Demonstrate the failure case: a malformed callback is rejected.
try:
    parse_delivery_callback({"sms_id": "abc-123"})
except ValidationError as e:
    print(f"Correctly rejected malformed callback: {e}")
