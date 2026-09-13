import pytest

from kalourmade_arkesel.delivery_callback import parse_delivery_callback
from kalourmade_arkesel.exceptions import ValidationError


def test_parse_returns_sms_id_and_status():
    result = parse_delivery_callback({"sms_id": "abc-123", "status": "DELIVERED"})

    assert result["sms_id"] == "abc-123"
    assert result["status"] == "DELIVERED"


def test_parse_raises_when_sms_id_missing():
    with pytest.raises(ValidationError):
        parse_delivery_callback({"status": "DELIVERED"})


def test_parse_raises_when_status_missing():
    with pytest.raises(ValidationError):
        parse_delivery_callback({"sms_id": "abc-123"})
