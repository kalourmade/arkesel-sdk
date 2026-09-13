<?php

declare(strict_types=1);

namespace Kalourmade\Arkesel\Balance;

final class Balance
{
    public function __construct(
        public readonly string $smsBalance,
        public readonly string $mainBalance,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            smsBalance: $data['sms_balance'],
            mainBalance: $data['main_balance'],
        );
    }
}
