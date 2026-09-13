from dataclasses import dataclass
from enum import Enum
from typing import List, Optional


class DeliveryStatus(str, Enum):
    DELIVERED = "DELIVERED"
    SUBMITTED = "SUBMITTED"
    PROHIBITED = "PROHIBITED"
    QUEUED = "QUEUED"
    NOT_DELIVERED = "NOT_DELIVERED"
    EXPIRED = "EXPIRED"


@dataclass(frozen=True)
class SmsDetails:
    id: str
    status: DeliveryStatus
    sender: str
    recipient: str
    message: str
    message_count: int
    sent_at_time: str

    @staticmethod
    def from_dict(data: dict) -> "SmsDetails":
        return SmsDetails(
            id=data["ID"],
            status=DeliveryStatus(data["status"]),
            sender=data["sender"],
            recipient=data["recipient"],
            message=data["message"],
            message_count=data["message_count"],
            sent_at_time=data["sent_at_time"],
        )


@dataclass(frozen=True)
class SendResultEntry:
    recipient: str
    id: str


@dataclass(frozen=True)
class SendResponse:
    results: List[SendResultEntry]
    invalid_numbers: List[str]
    message: Optional[str]

    @staticmethod
    def from_dict(data: dict) -> "SendResponse":
        results = []
        invalid_numbers: List[str] = []

        for entry in data.get("data", []):
            if "recipient" in entry and "id" in entry:
                results.append(SendResultEntry(recipient=entry["recipient"], id=entry["id"]))
            elif "invalid numbers" in entry:
                invalid_numbers.extend(entry["invalid numbers"])

        return SendResponse(results=results, invalid_numbers=invalid_numbers, message=data.get("message"))


@dataclass(frozen=True)
class ReportEntry:
    status: Optional[DeliveryStatus]
    sender: Optional[str]
    recipient: Optional[str]
    message: Optional[str]
    message_count: Optional[int]
    sent_at_time: Optional[str]
    error: Optional[str]

    @staticmethod
    def from_dict(data: dict) -> "ReportEntry":
        if data.get("status") == "error":
            return ReportEntry(None, None, None, None, None, None, data["response"])

        return ReportEntry(
            status=DeliveryStatus(data["status"]),
            sender=data["sender"],
            recipient=data["recipient"],
            message=data["message"],
            message_count=data["message_count"],
            sent_at_time=data["sent_at_time"],
            error=None,
        )


@dataclass(frozen=True)
class Balance:
    sms_balance: str
    main_balance: str

    @staticmethod
    def from_dict(data: dict) -> "Balance":
        return Balance(sms_balance=data["sms_balance"], main_balance=data["main_balance"])


@dataclass(frozen=True)
class OtpGenerateResult:
    code: str
    message: str
    ussd_code: Optional[str]

    @staticmethod
    def from_dict(data: dict) -> "OtpGenerateResult":
        return OtpGenerateResult(code=data["code"], message=data["message"], ussd_code=data.get("ussd_code"))


@dataclass(frozen=True)
class OtpVerifyResult:
    code: str
    message: str

    @staticmethod
    def from_dict(data: dict) -> "OtpVerifyResult":
        return OtpVerifyResult(code=data["code"], message=data["message"])
