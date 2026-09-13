import json

from kalourmade_arkesel.balance import BalanceResource
from tests.fakes import FakeTransport


def test_get_fetches_balance_details():
    body = json.dumps({"status": "success", "data": {"sms_balance": "2003", "main_balance": "GHS 20.99"}})
    transport = FakeTransport([(200, body)])
    resource = BalanceResource(transport)

    balance = resource.get()

    assert balance.sms_balance == "2003"
    assert transport.requests[0]["method"] == "GET"
    assert transport.requests[0]["path"] == "/api/v2/clients/balance-details"
