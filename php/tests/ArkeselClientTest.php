<?php

declare(strict_types=1);

namespace Kalourmade\Arkesel\Tests;

use Kalourmade\Arkesel\ArkeselClient;
use Kalourmade\Arkesel\Balance\BalanceResource;
use Kalourmade\Arkesel\Groups\GroupsResource;
use Kalourmade\Arkesel\Otp\OtpResource;
use Kalourmade\Arkesel\Sms\SmsResource;
use PHPUnit\Framework\TestCase;

final class ArkeselClientTest extends TestCase
{
    public function test_client_exposes_all_resources(): void
    {
        $client = new ArkeselClient('arkesel_test_key');

        $this->assertInstanceOf(SmsResource::class, $client->sms);
        $this->assertInstanceOf(BalanceResource::class, $client->balance);
        $this->assertInstanceOf(GroupsResource::class, $client->groups);
        $this->assertInstanceOf(OtpResource::class, $client->otp);
    }
}
