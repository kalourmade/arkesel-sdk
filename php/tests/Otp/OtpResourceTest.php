<?php

declare(strict_types=1);

namespace Kalourmade\Arkesel\Tests\Otp;

use Kalourmade\Arkesel\Exceptions\ValidationError;
use Kalourmade\Arkesel\Otp\OtpResource;
use Kalourmade\Arkesel\Tests\Fakes\FakeHttpTransport;
use PHPUnit\Framework\TestCase;

final class OtpResourceTest extends TestCase
{
    public function test_generate_posts_snake_case_body_and_returns_result(): void
    {
        $body = json_encode(['code' => '1000', 'ussd_code' => '*928*01#', 'message' => 'Successful, OTP is being processed for delivery']);
        $transport = new FakeHttpTransport([['status' => 200, 'body' => $body]]);
        $resource = new OtpResource($transport);

        $result = $resource->generate(5, 6, 'sms', 'This is OTP from Arkesel, %otp_code%', '233544919953', 'Arkesel', 'numeric');

        $this->assertSame('1000', $result->code);
        $this->assertSame('*928*01#', $result->ussdCode);
        $this->assertSame('/api/otp/generate', $transport->requests[0]['path']);
        $this->assertSame([
            'expiry' => 5,
            'length' => 6,
            'medium' => 'sms',
            'message' => 'This is OTP from Arkesel, %otp_code%',
            'number' => '233544919953',
            'sender_id' => 'Arkesel',
            'type' => 'numeric',
        ], $transport->requests[0]['body']);
    }

    public function test_generate_result_has_null_ussd_code_when_absent(): void
    {
        $body = json_encode(['code' => '1000', 'message' => 'Successful']);
        $transport = new FakeHttpTransport([['status' => 200, 'body' => $body]]);
        $resource = new OtpResource($transport);

        $result = $resource->generate(5, 6, 'sms', 'msg %otp_code%', '233544919953', 'Arkesel', 'numeric');

        $this->assertNull($result->ussdCode);
    }

    public function test_verify_posts_code_and_number(): void
    {
        $body = json_encode(['code' => '1100', 'message' => 'Successful']);
        $transport = new FakeHttpTransport([['status' => 200, 'body' => $body]]);
        $resource = new OtpResource($transport);

        $result = $resource->verify('173882', '233544919953');

        $this->assertSame('1100', $result->code);
        $this->assertSame('/api/otp/verify', $transport->requests[0]['path']);
        $this->assertSame(['code' => '173882', 'number' => '233544919953'], $transport->requests[0]['body']);
    }

    public function test_error_status_raises_mapped_exception(): void
    {
        $transport = new FakeHttpTransport([['status' => 422, 'body' => '{}']]);
        $resource = new OtpResource($transport);

        $this->expectException(ValidationError::class);
        $resource->verify('', '233544919953');
    }
}
