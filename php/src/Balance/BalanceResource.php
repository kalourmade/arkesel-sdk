<?php

declare(strict_types=1);

namespace Kalourmade\Arkesel\Balance;

use Kalourmade\Arkesel\Http\ErrorMapper;
use Kalourmade\Arkesel\Http\HttpTransport;

final class BalanceResource
{
    public function __construct(private readonly HttpTransport $transport)
    {
    }

    public function get(): Balance
    {
        $response = $this->transport->request('GET', '/api/v2/clients/balance-details');
        ErrorMapper::throwIfError($response['status'], $response['body']);

        $decoded = json_decode($response['body'], true, flags: JSON_THROW_ON_ERROR);

        return Balance::fromArray($decoded['data']);
    }
}
