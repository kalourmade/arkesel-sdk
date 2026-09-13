<?php

declare(strict_types=1);

namespace Kalourmade\Arkesel\Tests;

use Kalourmade\Arkesel\DeliveryCallback;
use Kalourmade\Arkesel\Exceptions\ValidationError;
use PHPUnit\Framework\TestCase;

final class DeliveryCallbackTest extends TestCase
{
    public function test_parse_returns_sms_id_and_status(): void
    {
        $result = DeliveryCallback::parse(['sms_id' => 'abc-123', 'status' => 'DELIVERED']);

        $this->assertSame('abc-123', $result['smsId']);
        $this->assertSame('DELIVERED', $result['status']);
    }

    public function test_parse_throws_when_sms_id_missing(): void
    {
        $this->expectException(ValidationError::class);
        DeliveryCallback::parse(['status' => 'DELIVERED']);
    }

    public function test_parse_throws_when_status_missing(): void
    {
        $this->expectException(ValidationError::class);
        DeliveryCallback::parse(['sms_id' => 'abc-123']);
    }
}
