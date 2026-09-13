<?php

declare(strict_types=1);

namespace Kalourmade\Arkesel\Otp;

final class OtpGenerateResult
{
    public function __construct(
        public readonly string $code,
        public readonly string $message,
        public readonly ?string $ussdCode,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            code: $data['code'],
            message: $data['message'],
            ussdCode: $data['ussd_code'] ?? null,
        );
    }
}
