<?php

declare(strict_types=1);

namespace Kalourmade\Arkesel\Tests\Balance;

use Kalourmade\Arkesel\Balance\Balance;
use PHPUnit\Framework\TestCase;

final class BalanceTest extends TestCase
{
    public function test_from_array_maps_fields(): void
    {
        $balance = Balance::fromArray([
            'sms_balance' => '2003',
            'main_balance' => 'GHS 20.99',
        ]);

        $this->assertSame('2003', $balance->smsBalance);
        $this->assertSame('GHS 20.99', $balance->mainBalance);
    }
}
