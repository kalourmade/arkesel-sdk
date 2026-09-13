<?php

declare(strict_types=1);

namespace Kalourmade\Arkesel\Tests\Sms;

use Kalourmade\Arkesel\Sms\DeliveryStatus;
use Kalourmade\Arkesel\Sms\SmsDetails;
use PHPUnit\Framework\TestCase;

final class SmsDetailsTest extends TestCase
{
    public function test_from_array_maps_uppercase_id_field(): void
    {
        $details = SmsDetails::fromArray([
            'ID' => 'f3be70c1-3545-4677-b607-6b5f32202652',
            'status' => 'DELIVERED',
            'sender' => 'Arkesel',
            'recipient' => '233544919953',
            'message' => 'Welcome to version 2 of our API!',
            'message_count' => 1,
            'sent_at_time' => '2021-04-09 18:44:05',
        ]);

        $this->assertSame('f3be70c1-3545-4677-b607-6b5f32202652', $details->id);
        $this->assertSame(DeliveryStatus::Delivered, $details->status);
        $this->assertSame(1, $details->messageCount);
        $this->assertSame('2021-04-09 18:44:05', $details->sentAtTime);
    }
}
