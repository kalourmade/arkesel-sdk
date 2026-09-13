from .exceptions import ApiError, AuthenticationError, InsufficientBalanceError, ValidationError


def raise_for_status(status: int, raw_body: str) -> None:
    if status < 400:
        return

    message = f"Arkesel API error (status {status})"

    if status == 402:
        raise InsufficientBalanceError(message)
    if status == 403:
        raise AuthenticationError(message)
    if status == 422:
        raise ValidationError(message)
    raise ApiError(status, raw_body, message)
