<?php

declare(strict_types=1);

namespace Kalourmade\Arkesel\Sms;

final class SendResultEntry
{
    public function __construct(
        public readonly string $recipient,
        public readonly string $id,
    ) {
    }
}
