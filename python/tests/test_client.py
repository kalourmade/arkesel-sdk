from kalourmade_arkesel.balance import BalanceResource
from kalourmade_arkesel.client import ArkeselClient
from kalourmade_arkesel.groups import GroupsResource
from kalourmade_arkesel.otp import OtpResource
from kalourmade_arkesel.sms import SmsResource


def test_client_exposes_all_resources():
    client = ArkeselClient("arkesel_test_key")

    assert isinstance(client.sms, SmsResource)
    assert isinstance(client.balance, BalanceResource)
    assert isinstance(client.groups, GroupsResource)
    assert isinstance(client.otp, OtpResource)
