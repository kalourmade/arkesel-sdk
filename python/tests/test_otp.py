import json

import pytest

from kalourmade_arkesel.exceptions import ValidationError
from kalourmade_arkesel.otp import OtpResource
from tests.fakes import FakeTransport


def test_generate_posts_snake_case_body_and_returns_result():
    body = json.dumps({"code": "1000", "ussd_code": "*928*01#", "message": "Successful, OTP is being processed for delivery"})
    transport = FakeTransport([(200, body)])
    resource = OtpResource(transport)

    result = resource.generate(
        expiry=5, length=6, medium="sms",
        message="This is OTP from Arkesel, %otp_code%",
        number="233544919953", sender_id="Arkesel", type="numeric",
    )

    assert result.code == "1000"
    assert result.ussd_code == "*928*01#"
    assert transport.requests[0]["path"] == "/api/otp/generate"
    assert transport.requests[0]["json_body"] == {
        "expiry": 5,
        "length": 6,
        "medium": "sms",
        "message": "This is OTP from Arkesel, %otp_code%",
        "number": "233544919953",
        "sender_id": "Arkesel",
        "type": "numeric",
    }


def test_generate_result_has_none_ussd_code_when_absent():
    body = json.dumps({"code": "1000", "message": "Successful"})
    transport = FakeTransport([(200, body)])
    resource = OtpResource(transport)

    result = resource.generate(
        expiry=5, length=6, medium="sms", message="msg %otp_code%",
        number="233544919953", sender_id="Arkesel", type="numeric",
    )

    assert result.ussd_code is None


def test_verify_posts_code_and_number():
    body = json.dumps({"code": "1100", "message": "Successful"})
    transport = FakeTransport([(200, body)])
    resource = OtpResource(transport)

    result = resource.verify(code="173882", number="233544919953")

    assert result.code == "1100"
    assert transport.requests[0]["path"] == "/api/otp/verify"
    assert transport.requests[0]["json_body"] == {"code": "173882", "number": "233544919953"}


def test_error_status_raises_mapped_exception():
    transport = FakeTransport([(422, "{}")])
    resource = OtpResource(transport)

    with pytest.raises(ValidationError):
        resource.verify(code="", number="233544919953")
