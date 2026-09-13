<?php

declare(strict_types=1);

namespace Kalourmade\Arkesel;

use Kalourmade\Arkesel\Balance\BalanceResource;
use Kalourmade\Arkesel\Groups\GroupsResource;
use Kalourmade\Arkesel\Http\CurlHttpTransport;
use Kalourmade\Arkesel\Otp\OtpResource;
use Kalourmade\Arkesel\Sms\SmsResource;

final class ArkeselClient
{
    public readonly SmsResource $sms;
    public readonly BalanceResource $balance;
    public readonly GroupsResource $groups;
    public readonly OtpResource $otp;

    public function __construct(
        string $apiKey,
        string $baseUrl = 'https://sms.arkesel.com',
        int $timeout = 30
    ) {
        $transport = new CurlHttpTransport($apiKey, $baseUrl, $timeout);
        $this->sms = new SmsResource($transport);
        $this->balance = new BalanceResource($transport);
        $this->groups = new GroupsResource($transport);
        $this->otp = new OtpResource($transport);
    }
}
