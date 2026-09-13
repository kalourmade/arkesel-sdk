<?php

declare(strict_types=1);

namespace Kalourmade\Arkesel\Tests\Groups;

use Kalourmade\Arkesel\Exceptions\ValidationError;
use Kalourmade\Arkesel\Groups\GroupsResource;
use Kalourmade\Arkesel\Tests\Fakes\FakeHttpTransport;
use PHPUnit\Framework\TestCase;

final class GroupsResourceTest extends TestCase
{
    public function test_create_posts_group_name(): void
    {
        $body = json_encode(['status' => 'success', 'message' => 'Contact group has been created successfully!']);
        $transport = new FakeHttpTransport([['status' => 201, 'body' => $body]]);
        $resource = new GroupsResource($transport);

        $resource->create('New Customers');

        $this->assertSame('/api/v2/contacts/groups', $transport->requests[0]['path']);
        $this->assertSame(['group_name' => 'New Customers'], $transport->requests[0]['body']);
    }

    public function test_add_contacts_posts_group_name_and_contacts(): void
    {
        $body = json_encode(['status' => 'success', 'message' => 'Contacts have been created successfully!']);
        $transport = new FakeHttpTransport([['status' => 201, 'body' => $body]]);
        $resource = new GroupsResource($transport);

        $resource->addContacts('New Customers', [
            ['phone_number' => '233544919953'],
            ['phone_number' => '233544919953', 'first_name' => 'Arkesel'],
        ]);

        $this->assertSame('/api/v2/contacts', $transport->requests[0]['path']);
        $this->assertSame([
            'group_name' => 'New Customers',
            'contacts' => [
                ['phone_number' => '233544919953'],
                ['phone_number' => '233544919953', 'first_name' => 'Arkesel'],
            ],
        ], $transport->requests[0]['body']);
    }

    public function test_create_raises_validation_error_on_422(): void
    {
        $transport = new FakeHttpTransport([['status' => 422, 'body' => '{}']]);
        $resource = new GroupsResource($transport);

        $this->expectException(ValidationError::class);
        $resource->create('');
    }
}
