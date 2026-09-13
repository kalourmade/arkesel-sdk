import json

from .error_mapping import raise_for_status
from .http import Transport
from .models import Balance


class BalanceResource:
    def __init__(self, transport: Transport):
        self._transport = transport

    def get(self) -> Balance:
        status, raw = self._transport.request("GET", "/api/v2/clients/balance-details")
        raise_for_status(status, raw)
        return Balance.from_dict(json.loads(raw)["data"])
