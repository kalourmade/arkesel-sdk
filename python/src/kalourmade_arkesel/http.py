from typing import Optional, Protocol

import httpx

from .exceptions import NetworkError


class Transport(Protocol):
    def request(
        self,
        method: str,
        path: str,
        query: Optional[dict] = None,
        json_body: Optional[dict] = None,
    ) -> tuple[int, str]:
        ...


class HttpxTransport:
    def __init__(
        self,
        api_key: str,
        base_url: str = "https://sms.arkesel.com",
        timeout: float = 30.0,
    ):
        self._client = httpx.Client(
            base_url=base_url,
            headers={"api-key": api_key},
            timeout=timeout,
        )

    def request(
        self,
        method: str,
        path: str,
        query: Optional[dict] = None,
        json_body: Optional[dict] = None,
    ) -> tuple[int, str]:
        try:
            response = self._client.request(method, path, params=query, json=json_body)
        except httpx.HTTPError as exc:
            raise NetworkError(str(exc)) from exc

        return response.status_code, response.text
