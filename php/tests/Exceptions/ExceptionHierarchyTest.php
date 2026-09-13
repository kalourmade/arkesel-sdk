<?php

declare(strict_types=1);

namespace Kalourmade\Arkesel\Tests\Exceptions;

use Kalourmade\Arkesel\Exceptions\ApiError;
use Kalourmade\Arkesel\Exceptions\ArkeselError;
use Kalourmade\Arkesel\Exceptions\AuthenticationError;
use Kalourmade\Arkesel\Exceptions\InsufficientBalanceError;
use Kalourmade\Arkesel\Exceptions\NetworkError;
use Kalourmade\Arkesel\Exceptions\ValidationError;
use PHPUnit\Framework\TestCase;

final class ExceptionHierarchyTest extends TestCase
{
    public function test_all_exceptions_extend_arkesel_error(): void
    {
        $this->assertInstanceOf(ArkeselError::class, new AuthenticationError('x'));
        $this->assertInstanceOf(ArkeselError::class, new InsufficientBalanceError('x'));
        $this->assertInstanceOf(ArkeselError::class, new ValidationError('x'));
        $this->assertInstanceOf(ArkeselError::class, new NetworkError('x'));
        $this->assertInstanceOf(ArkeselError::class, new ApiError(500, 'body', 'x'));
    }

    public function test_api_error_carries_status_and_raw_body(): void
    {
        $error = new ApiError(500, '{"status":"failed"}', 'Arkesel API error (status 500)');

        $this->assertSame(500, $error->statusCode);
        $this->assertSame('{"status":"failed"}', $error->rawBody);
        $this->assertSame('Arkesel API error (status 500)', $error->getMessage());
    }
}
