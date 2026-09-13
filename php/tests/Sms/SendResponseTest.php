<?php

declare(strict_types=1);

namespace Kalourmade\Arkesel\Tests\Sms;

use Kalourmade\Arkesel\Sms\SendResponse;
use PHPUnit\Framework\TestCase;

final class SendResponseTest extends TestCase
{
    public function test_from_array_separates_results_and_invalid_numbers(): void
    {
        $response = SendResponse::fromArray([
            'status' => 'success',
            'data' => [
                ['recipient' => '233544919953', 'id' => '9b752841-7ee7-4d40-b4fe-768bfb1da4f0'],
                ['recipient' => '233544919953', 'id' => '7ea01acd-485c-4df3-b646-e9e24430e145'],
                ['invalid numbers' => ['22354674948']],
            ],
        ]);

        $this->assertCount(2, $response->results);
        $this->assertSame('9b752841-7ee7-4d40-b4fe-768bfb1da4f0', $response->results[0]->id);
        $this->assertSame(['22354674948'], $response->invalidNumbers);
        $this->assertNull($response->message);
    }

    public function test_from_array_handles_scheduled_send_with_no_data_key(): void
    {
        $response = SendResponse::fromArray([
            'status' => 'success',
            'message' => 'SMS request sent successfully!',
        ]);

        $this->assertSame([], $response->results);
        $this->assertSame([], $response->invalidNumbers);
        $this->assertSame('SMS request sent successfully!', $response->message);
    }
}
