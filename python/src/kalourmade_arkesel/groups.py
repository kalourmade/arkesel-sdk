from typing import List

from .error_mapping import raise_for_status
from .http import Transport


class GroupsResource:
    def __init__(self, transport: Transport):
        self._transport = transport

    def create(self, group_name: str) -> None:
        status, raw = self._transport.request(
            "POST", "/api/v2/contacts/groups", json_body={"group_name": group_name}
        )
        raise_for_status(status, raw)

    def add_contacts(self, group_name: str, contacts: List[dict]) -> None:
        status, raw = self._transport.request(
            "POST", "/api/v2/contacts", json_body={"group_name": group_name, "contacts": contacts}
        )
        raise_for_status(status, raw)
