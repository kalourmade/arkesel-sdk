<?php

declare(strict_types=1);

namespace Kalourmade\Arkesel\Tests\Balance;

use Kalourmade\Arkesel\Balance\BalanceResource;
use Kalourmade\Arkesel\Tests\Fakes\FakeHttpTransport;
use PHPUnit\Framework\TestCase;

final class BalanceResourceTest extends TestCase
{
    public function test_get_fetches_balance_details(): void
    {
        $body = json_encode([
            'status' => 'success',
            'data' => ['sms_balance' => '2003', 'main_balance' => 'GHS 20.99'],
        ]);
        $transport = new FakeHttpTransport([['status' => 200, 'body' => $body]]);
        $resource = new BalanceResource($transport);

        $balance = $resource->get();

        $this->assertSame('2003', $balance->smsBalance);
        $this->assertSame('GET', $transport->requests[0]['method']);
        $this->assertSame('/api/v2/clients/balance-details', $transport->requests[0]['path']);
    }
}
