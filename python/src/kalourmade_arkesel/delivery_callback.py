from .exceptions import ValidationError


def parse_delivery_callback(query: dict) -> dict:
    if "sms_id" not in query or "status" not in query:
        raise ValidationError("Missing sms_id or status query parameter")

    return {"sms_id": query["sms_id"], "status": query["status"]}
