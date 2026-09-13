<?php

declare(strict_types=1);

namespace Kalourmade\Arkesel\Otp;

final class OtpVerifyResult
{
    public function __construct(
        public readonly string $code,
        public readonly string $message,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(code: $data['code'], message: $data['message']);
    }
}
