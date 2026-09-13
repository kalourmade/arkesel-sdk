import json

import pytest

from kalourmade_arkesel.exceptions import InsufficientBalanceError
from kalourmade_arkesel.sms import SmsResource
from tests.fakes import FakeTransport


def test_send_posts_to_sms_send_and_returns_parsed_response():
    body = json.dumps({
        "status": "success",
        "data": [{"recipient": "233544919953", "id": "abc-123"}],
    })
    transport = FakeTransport([(200, body)])
    resource = SmsResource(transport)

    response = resource.send(sender="Arkesel", recipients=["233544919953"], message="Hello world")

    assert len(response.results) == 1
    assert transport.requests[0]["method"] == "POST"
    assert transport.requests[0]["path"] == "/api/v2/sms/send"
    assert transport.requests[0]["json_body"] == {
        "sender": "Arkesel",
        "recipients": ["233544919953"],
        "message": "Hello world",
    }


def test_send_includes_optional_fields_when_provided():
    transport = FakeTransport([(200, json.dumps({"status": "success", "data": []}))])
    resource = SmsResource(transport)

    resource.send(
        sender="Arkesel",
        recipients=["233544919953"],
        message="Hello",
        callback_url="https://example.com/cb",
        scheduled_date="2026-09-12 10:00 AM",
        use_case="promotional",
        sandbox=True,
    )

    assert transport.requests[0]["json_body"] == {
        "sender": "Arkesel",
        "recipients": ["233544919953"],
        "message": "Hello",
        "callback_url": "https://example.com/cb",
        "scheduled_date": "2026-09-12 10:00 AM",
        "use_case": "promotional",
        "sandbox": True,
    }


def test_get_fetches_sms_details_by_id():
    body = json.dumps({
        "status": "success",
        "data": {
            "ID": "abc-123",
            "status": "DELIVERED",
            "sender": "Arkesel",
            "recipient": "233544919953",
            "message": "Hello",
            "message_count": 1,
            "sent_at_time": "2021-04-09 18:44:05",
        },
    })
    transport = FakeTransport([(200, body)])
    resource = SmsResource(transport)

    details = resource.get("abc-123")

    assert details.id == "abc-123"
    assert transport.requests[0]["path"] == "/api/v2/sms/abc-123"


def test_message_reports_posts_ids_and_returns_keyed_dict():
    body = json.dumps({
        "status": "success",
        "data": {
            "abc-123": {
                "sender": "Arkesel",
                "recipient": "233540000000",
                "status": "DELIVERED",
                "message": "Hello",
                "message_count": 1,
                "sent_at_time": "2021-04-09 18:44:05",
            },
            "def-456": {"status": "error", "response": "message does not exist"},
        },
    })
    transport = FakeTransport([(200, body)])
    resource = SmsResource(transport)

    reports = resource.message_reports(["abc-123", "def-456"])

    assert transport.requests[0]["path"] == "/api/v2/sms/message-reports"
    assert transport.requests[0]["json_body"] == {"msg_ids": ["abc-123", "def-456"]}
    assert reports["abc-123"].error is None
    assert reports["def-456"].error == "message does not exist"


def test_send_to_group_posts_to_contact_group_endpoint():
    body = json.dumps({"status": "success", "message": "SMS request sent successfully!"})
    transport = FakeTransport([(200, body)])
    resource = SmsResource(transport)

    resource.send_to_group(sender="Arkesel", group_name="New Customers", message="Hello group")

    assert transport.requests[0]["path"] == "/api/v2/sms/send/contact-group"
    assert transport.requests[0]["json_body"] == {
        "sender": "Arkesel",
        "group_name": "New Customers",
        "message": "Hello group",
    }


def test_error_status_raises_mapped_exception():
    transport = FakeTransport([(402, "{}")])
    resource = SmsResource(transport)

    with pytest.raises(InsufficientBalanceError):
        resource.send(sender="Arkesel", recipients=["233544919953"], message="Hello")
