<?php

declare(strict_types=1);

namespace Kalourmade\Arkesel\Http;

use Kalourmade\Arkesel\Exceptions\NetworkError;

final class CurlHttpTransport implements HttpTransport
{
    public function __construct(
        private readonly string $apiKey,
        private readonly string $baseUrl = 'https://sms.arkesel.com',
        private readonly int $timeout = 30
    ) {
    }

    public function request(string $method, string $path, ?array $query = null, ?array $body = null): array
    {
        $url = rtrim($this->baseUrl, '/') . $path;
        if ($query !== null && $query !== []) {
            $url .= '?' . http_build_query($query);
        }

        $handle = curl_init($url);
        $headers = [
            'api-key: ' . $this->apiKey,
            'Content-Type: application/json',
            'Accept: application/json',
        ];

        $options = [
            CURLOPT_CUSTOMREQUEST => strtoupper($method),
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $this->timeout,
        ];

        if ($body !== null) {
            $options[CURLOPT_POSTFIELDS] = json_encode($body, JSON_THROW_ON_ERROR);
        }

        curl_setopt_array($handle, $options);

        $responseBody = curl_exec($handle);

        if ($responseBody === false) {
            $error = curl_error($handle);
            curl_close($handle);
            throw new NetworkError($error);
        }

        $status = (int) curl_getinfo($handle, CURLINFO_HTTP_CODE);
        curl_close($handle);

        return ['status' => $status, 'body' => (string) $responseBody];
    }
}
