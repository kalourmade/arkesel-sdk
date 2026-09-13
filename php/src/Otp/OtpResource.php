<?php

declare(strict_types=1);

namespace Kalourmade\Arkesel\Otp;

use Kalourmade\Arkesel\Http\ErrorMapper;
use Kalourmade\Arkesel\Http\HttpTransport;

final class OtpResource
{
    public function __construct(private readonly HttpTransport $transport)
    {
    }

    public function generate(
        int $expiry,
        int $length,
        string $medium,
        string $message,
        string $number,
        string $senderId,
        string $type,
    ): OtpGenerateResult {
        $response = $this->transport->request('POST', '/api/otp/generate', body: [
            'expiry' => $expiry,
            'length' => $length,
            'medium' => $medium,
            'message' => $message,
            'number' => $number,
            'sender_id' => $senderId,
            'type' => $type,
        ]);
        ErrorMapper::throwIfError($response['status'], $response['body']);

        return OtpGenerateResult::fromArray(json_decode($response['body'], true, flags: JSON_THROW_ON_ERROR));
    }

    public function verify(string $code, string $number): OtpVerifyResult
    {
        $response = $this->transport->request('POST', '/api/otp/verify', body: [
            'code' => $code,
            'number' => $number,
        ]);
        ErrorMapper::throwIfError($response['status'], $response['body']);

        return OtpVerifyResult::fromArray(json_decode($response['body'], true, flags: JSON_THROW_ON_ERROR));
    }
}
