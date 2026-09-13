<?php

declare(strict_types=1);

namespace Kalourmade\Arkesel\Groups;

use Kalourmade\Arkesel\Http\ErrorMapper;
use Kalourmade\Arkesel\Http\HttpTransport;

final class GroupsResource
{
    public function __construct(private readonly HttpTransport $transport)
    {
    }

    public function create(string $groupName): void
    {
        $response = $this->transport->request('POST', '/api/v2/contacts/groups', body: [
            'group_name' => $groupName,
        ]);
        ErrorMapper::throwIfError($response['status'], $response['body']);
    }

    /**
     * @param array<array{phone_number:string, first_name?:string, last_name?:string, company?:string, email_address?:string, user_name?:string}> $contacts
     */
    public function addContacts(string $groupName, array $contacts): void
    {
        $response = $this->transport->request('POST', '/api/v2/contacts', body: [
            'group_name' => $groupName,
            'contacts' => $contacts,
        ]);
        ErrorMapper::throwIfError($response['status'], $response['body']);
    }
}
