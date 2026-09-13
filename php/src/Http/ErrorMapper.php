<?php

declare(strict_types=1);

namespace Kalourmade\Arkesel\Http;

use Kalourmade\Arkesel\Exceptions\ApiError;
use Kalourmade\Arkesel\Exceptions\AuthenticationError;
use Kalourmade\Arkesel\Exceptions\InsufficientBalanceError;
use Kalourmade\Arkesel\Exceptions\ValidationError;

final class ErrorMapper
{
    public static function throwIfError(int $status, string $rawBody): void
    {
        if ($status < 400) {
            return;
        }

        $message = "Arkesel API error (status {$status})";

        match (true) {
            $status === 402 => throw new InsufficientBalanceError($message),
            $status === 403 => throw new AuthenticationError($message),
            $status === 422 => throw new ValidationError($message),
            default => throw new ApiError($status, $rawBody, $message),
        };
    }
}
