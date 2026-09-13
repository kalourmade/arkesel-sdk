<?php

declare(strict_types=1);

namespace Kalourmade\Arkesel\Tests\Http;

use Kalourmade\Arkesel\Exceptions\ApiError;
use Kalourmade\Arkesel\Exceptions\AuthenticationError;
use Kalourmade\Arkesel\Exceptions\InsufficientBalanceError;
use Kalourmade\Arkesel\Exceptions\ValidationError;
use Kalourmade\Arkesel\Http\ErrorMapper;
use PHPUnit\Framework\TestCase;

final class ErrorMapperTest extends TestCase
{
    public function test_ok_status_does_not_throw(): void
    {
        ErrorMapper::throwIfError(200, '{}');
        $this->addToAssertionCount(1);
    }

    public function test_402_throws_insufficient_balance_error(): void
    {
        $this->expectException(InsufficientBalanceError::class);
        ErrorMapper::throwIfError(402, '{}');
    }

    public function test_403_throws_authentication_error(): void
    {
        $this->expectException(AuthenticationError::class);
        ErrorMapper::throwIfError(403, '{}');
    }

    public function test_422_throws_validation_error(): void
    {
        $this->expectException(ValidationError::class);
        ErrorMapper::throwIfError(422, '{}');
    }

    public function test_other_4xx_5xx_throw_api_error_with_status_and_body(): void
    {
        try {
            ErrorMapper::throwIfError(500, '{"status":"failed"}');
            $this->fail('Expected ApiError');
        } catch (ApiError $e) {
            $this->assertSame(500, $e->statusCode);
            $this->assertSame('{"status":"failed"}', $e->rawBody);
        }
    }
}
