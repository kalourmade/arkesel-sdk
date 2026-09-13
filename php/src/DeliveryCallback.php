<?php

declare(strict_types=1);

namespace Kalourmade\Arkesel;

use Kalourmade\Arkesel\Exceptions\ValidationError;

final class DeliveryCallback
{
    /**
     * @param array<string,string> $query
     * @return array{smsId: string, status: string}
     */
    public static function parse(array $query): array
    {
        if (!isset($query['sms_id'], $query['status'])) {
            throw new ValidationError('Missing sms_id or status query parameter');
        }

        return ['smsId' => $query['sms_id'], 'status' => $query['status']];
    }
}
