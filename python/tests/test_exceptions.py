from kalourmade_arkesel.exceptions import (
    ApiError,
    ArkeselError,
    AuthenticationError,
    InsufficientBalanceError,
    NetworkError,
    ValidationError,
)


def test_all_exceptions_extend_arkesel_error():
    assert issubclass(AuthenticationError, ArkeselError)
    assert issubclass(InsufficientBalanceError, ArkeselError)
    assert issubclass(ValidationError, ArkeselError)
    assert issubclass(NetworkError, ArkeselError)
    assert issubclass(ApiError, ArkeselError)


def test_api_error_carries_status_and_raw_body():
    error = ApiError(500, '{"status":"failed"}', "Arkesel API error (status 500)")

    assert error.status_code == 500
    assert error.raw_body == '{"status":"failed"}'
    assert str(error) == "Arkesel API error (status 500)"
