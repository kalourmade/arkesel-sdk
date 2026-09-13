class ArkeselError(Exception):
    """Base exception for all Arkesel SDK errors."""


class AuthenticationError(ArkeselError):
    """Raised on 403 responses (bad/missing api-key, or an inactive gateway)."""


class InsufficientBalanceError(ArkeselError):
    """Raised on 402 responses."""


class ValidationError(ArkeselError):
    """Raised on 422 responses, and by DeliveryCallback.parse on a malformed callback."""


class ApiError(ArkeselError):
    """Raised on other 4xx/5xx responses."""

    def __init__(self, status_code: int, raw_body: str, message: str):
        super().__init__(message)
        self.status_code = status_code
        self.raw_body = raw_body


class NetworkError(ArkeselError):
    """Raised when the request could not reach Arkesel."""
