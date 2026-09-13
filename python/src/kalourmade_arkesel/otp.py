import json

from .error_mapping import raise_for_status
from .http import Transport
from .models import OtpGenerateResult, OtpVerifyResult


class OtpResource:
    def __init__(self, transport: Transport):
        self._transport = transport

    def generate(
        self,
        expiry: int,
        length: int,
        medium: str,
        message: str,
        number: str,
        sender_id: str,
        type: str,
    ) -> OtpGenerateResult:
        body = {
            "expiry": expiry,
            "length": length,
            "medium": medium,
            "message": message,
            "number": number,
            "sender_id": sender_id,
            "type": type,
        }
        status, raw = self._transport.request("POST", "/api/otp/generate", json_body=body)
        raise_for_status(status, raw)
        return OtpGenerateResult.from_dict(json.loads(raw))

    def verify(self, code: str, number: str) -> OtpVerifyResult:
        status, raw = self._transport.request(
            "POST", "/api/otp/verify", json_body={"code": code, "number": number}
        )
        raise_for_status(status, raw)
        return OtpVerifyResult.from_dict(json.loads(raw))
