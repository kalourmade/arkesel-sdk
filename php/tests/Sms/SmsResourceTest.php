<?php

declare(strict_types=1);

namespace Kalourmade\Arkesel\Tests\Sms;

use Kalourmade\Arkesel\Exceptions\InsufficientBalanceError;
use Kalourmade\Arkesel\Sms\SmsResource;
use Kalourmade\Arkesel\Tests\Fakes\FakeHttpTransport;
use PHPUnit\Framework\TestCase;

final class SmsResourceTest extends TestCase
{
    public function test_send_posts_to_sms_send_and_returns_parsed_response(): void
    {
        $body = json_encode([
            'status' => 'success',
            'data' => [
                ['recipient' => '233544919953', 'id' => 'abc-123'],
            ],
        ]);
        $transport = new FakeHttpTransport([['status' => 200, 'body' => $body]]);
        $resource = new SmsResource($transport);

        $response = $resource->send('Arkesel', ['233544919953'], 'Hello world');

        $this->assertCount(1, $response->results);
        $this->assertSame('POST', $transport->requests[0]['method']);
        $this->assertSame('/api/v2/sms/send', $transport->requests[0]['path']);
        $this->assertSame(
            ['sender' => 'Arkesel', 'recipients' => ['233544919953'], 'message' => 'Hello world'],
            $transport->requests[0]['body']
        );
    }

    public function test_send_includes_optional_fields_when_provided(): void
    {
        $transport = new FakeHttpTransport([
            ['status' => 200, 'body' => json_encode(['status' => 'success', 'data' => []])],
        ]);
        $resource = new SmsResource($transport);

        $resource->send(
            'Arkesel',
            ['233544919953'],
            'Hello',
            callbackUrl: 'https://example.com/cb',
            scheduledDate: '2026-09-12 10:00 AM',
            useCase: 'promotional',
            sandbox: true
        );

        $this->assertSame([
            'sender' => 'Arkesel',
            'recipients' => ['233544919953'],
            'message' => 'Hello',
            'callback_url' => 'https://example.com/cb',
            'scheduled_date' => '2026-09-12 10:00 AM',
            'use_case' => 'promotional',
            'sandbox' => true,
        ], $transport->requests[0]['body']);
    }

    public function test_get_fetches_sms_details_by_id(): void
    {
        $body = json_encode([
            'status' => 'success',
            'data' => [
                'ID' => 'abc-123',
                'status' => 'DELIVERED',
                'sender' => 'Arkesel',
                'recipient' => '233544919953',
                'message' => 'Hello',
                'message_count' => 1,
                'sent_at_time' => '2021-04-09 18:44:05',
            ],
        ]);
        $transport = new FakeHttpTransport([['status' => 200, 'body' => $body]]);
        $resource = new SmsResource($transport);

        $details = $resource->get('abc-123');

        $this->assertSame('abc-123', $details->id);
        $this->assertSame('/api/v2/sms/abc-123', $transport->requests[0]['path']);
    }

    public function test_message_reports_posts_ids_and_returns_keyed_map(): void
    {
        $body = json_encode([
            'status' => 'success',
            'data' => [
                'abc-123' => [
                    'sender' => 'Arkesel',
                    'recipient' => '233540000000',
                    'status' => 'DELIVERED',
                    'message' => 'Hello',
                    'message_count' => 1,
                    'sent_at_time' => '2021-04-09 18:44:05',
                ],
                'def-456' => ['status' => 'error', 'response' => 'message does not exist'],
            ],
        ]);
        $transport = new FakeHttpTransport([['status' => 200, 'body' => $body]]);
        $resource = new SmsResource($transport);

        $reports = $resource->messageReports(['abc-123', 'def-456']);

        $this->assertSame('/api/v2/sms/message-reports', $transport->requests[0]['path']);
        $this->assertSame(['msg_ids' => ['abc-123', 'def-456']], $transport->requests[0]['body']);
        $this->assertNull($reports['abc-123']->error);
        $this->assertSame('message does not exist', $reports['def-456']->error);
    }

    public function test_send_to_group_posts_to_contact_group_endpoint(): void
    {
        $body = json_encode(['status' => 'success', 'message' => 'SMS request sent successfully!']);
        $transport = new FakeHttpTransport([['status' => 200, 'body' => $body]]);
        $resource = new SmsResource($transport);

        $resource->sendToGroup('Arkesel', 'New Customers', 'Hello group');

        $this->assertSame('/api/v2/sms/send/contact-group', $transport->requests[0]['path']);
        $this->assertSame(
            ['sender' => 'Arkesel', 'group_name' => 'New Customers', 'message' => 'Hello group'],
            $transport->requests[0]['body']
        );
    }

    public function test_error_status_raises_mapped_exception(): void
    {
        $transport = new FakeHttpTransport([['status' => 402, 'body' => '{}']]);
        $resource = new SmsResource($transport);

        $this->expectException(InsufficientBalanceError::class);
        $resource->send('Arkesel', ['233544919953'], 'Hello');
    }
}
