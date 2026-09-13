import json
from typing import Dict, List, Optional

from .error_mapping import raise_for_status
from .http import Transport
from .models import ReportEntry, SendResponse, SmsDetails


class SmsResource:
    def __init__(self, transport: Transport):
        self._transport = transport

    def send(
        self,
        sender: str,
        recipients: List[str],
        message: str,
        callback_url: Optional[str] = None,
        scheduled_date: Optional[str] = None,
        use_case: Optional[str] = None,
        sandbox: Optional[bool] = None,
    ) -> SendResponse:
        body = {"sender": sender, "recipients": recipients, "message": message}
        if callback_url is not None:
            body["callback_url"] = callback_url
        if scheduled_date is not None:
            body["scheduled_date"] = scheduled_date
        if use_case is not None:
            body["use_case"] = use_case
        if sandbox is not None:
            body["sandbox"] = sandbox

        status, raw = self._transport.request("POST", "/api/v2/sms/send", json_body=body)
        raise_for_status(status, raw)
        return SendResponse.from_dict(json.loads(raw))

    def get(self, id: str) -> SmsDetails:
        status, raw = self._transport.request("GET", f"/api/v2/sms/{id}")
        raise_for_status(status, raw)
        return SmsDetails.from_dict(json.loads(raw)["data"])

    def message_reports(self, ids: List[str]) -> Dict[str, ReportEntry]:
        status, raw = self._transport.request(
            "POST", "/api/v2/sms/message-reports", json_body={"msg_ids": ids}
        )
        raise_for_status(status, raw)
        data = json.loads(raw)["data"]
        return {id: ReportEntry.from_dict(entry) for id, entry in data.items()}

    def send_to_group(self, sender: str, group_name: str, message: str) -> None:
        status, raw = self._transport.request(
            "POST",
            "/api/v2/sms/send/contact-group",
            json_body={"sender": sender, "group_name": group_name, "message": message},
        )
        raise_for_status(status, raw)
