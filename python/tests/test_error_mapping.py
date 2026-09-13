import pytest

from kalourmade_arkesel.error_mapping import raise_for_status
from kalourmade_arkesel.exceptions import (
    ApiError,
    AuthenticationError,
    InsufficientBalanceError,
    ValidationError,
)


def test_ok_status_does_not_raise():
    raise_for_status(200, "{}")


def test_402_raises_insufficient_balance_error():
    with pytest.raises(InsufficientBalanceError):
        raise_for_status(402, "{}")


def test_403_raises_authentication_error():
    with pytest.raises(AuthenticationError):
        raise_for_status(403, "{}")


def test_422_raises_validation_error():
    with pytest.raises(ValidationError):
        raise_for_status(422, "{}")


def test_other_4xx_5xx_raises_api_error_with_status_and_body():
    with pytest.raises(ApiError) as exc_info:
        raise_for_status(500, '{"status":"failed"}')

    assert exc_info.value.status_code == 500
    assert exc_info.value.raw_body == '{"status":"failed"}'
