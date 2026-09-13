from .balance import BalanceResource
from .groups import GroupsResource
from .http import HttpxTransport
from .otp import OtpResource
from .sms import SmsResource


class ArkeselClient:
    def __init__(
        self,
        api_key: str,
        base_url: str = "https://sms.arkesel.com",
        timeout: float = 30.0,
    ):
        transport = HttpxTransport(api_key, base_url, timeout)
        self.sms = SmsResource(transport)
        self.balance = BalanceResource(transport)
        self.groups = GroupsResource(transport)
        self.otp = OtpResource(transport)
