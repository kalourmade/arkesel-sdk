<?php

declare(strict_types=1);

namespace Kalourmade\Arkesel\Exceptions;

class ApiError extends ArkeselError
{
    public function __construct(
        public readonly int $statusCode,
        public readonly string $rawBody,
        string $message
    ) {
        parent::__construct($message);
    }
}
