import json

import pytest

from kalourmade_arkesel.exceptions import ValidationError
from kalourmade_arkesel.groups import GroupsResource
from tests.fakes import FakeTransport


def test_create_posts_group_name():
    body = json.dumps({"status": "success", "message": "Contact group has been created successfully!"})
    transport = FakeTransport([(201, body)])
    resource = GroupsResource(transport)

    resource.create("New Customers")

    assert transport.requests[0]["path"] == "/api/v2/contacts/groups"
    assert transport.requests[0]["json_body"] == {"group_name": "New Customers"}


def test_add_contacts_posts_group_name_and_contacts():
    body = json.dumps({"status": "success", "message": "Contacts have been created successfully!"})
    transport = FakeTransport([(201, body)])
    resource = GroupsResource(transport)

    resource.add_contacts("New Customers", [
        {"phone_number": "233544919953"},
        {"phone_number": "233544919953", "first_name": "Arkesel"},
    ])

    assert transport.requests[0]["path"] == "/api/v2/contacts"
    assert transport.requests[0]["json_body"] == {
        "group_name": "New Customers",
        "contacts": [
            {"phone_number": "233544919953"},
            {"phone_number": "233544919953", "first_name": "Arkesel"},
        ],
    }


def test_create_raises_validation_error_on_422():
    transport = FakeTransport([(422, "{}")])
    resource = GroupsResource(transport)

    with pytest.raises(ValidationError):
        resource.create("")
