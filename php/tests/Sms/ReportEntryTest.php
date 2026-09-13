<?php

declare(strict_types=1);

namespace Kalourmade\Arkesel\Tests\Sms;

use Kalourmade\Arkesel\Sms\DeliveryStatus;
use Kalourmade\Arkesel\Sms\ReportEntry;
use PHPUnit\Framework\TestCase;

final class ReportEntryTest extends TestCase
{
    public function test_from_array_maps_successful_report(): void
    {
        $entry = ReportEntry::fromArray([
            'sender' => 'Arkesel',
            'recipient' => '233540000000',
            'status' => 'DELIVERED',
            'message' => 'Welcome to version 2 of our API!',
            'message_count' => 1,
            'sent_at_time' => '2021-04-09 18:44:05',
        ]);

        $this->assertSame(DeliveryStatus::Delivered, $entry->status);
        $this->assertNull($entry->error);
    }

    public function test_from_array_maps_error_stub(): void
    {
        $entry = ReportEntry::fromArray([
            'status' => 'error',
            'response' => 'message does not exist',
        ]);

        $this->assertNull($entry->status);
        $this->assertSame('message does not exist', $entry->error);
    }
}
