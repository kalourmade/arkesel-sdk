<?php

declare(strict_types=1);

namespace Kalourmade\Arkesel\Http;

interface HttpTransport
{
    /**
     * @param array<string,scalar>|null $query
     * @param array<string,mixed>|null $body
     * @return array{status:int, body:string}
     */
    public function request(string $method, string $path, ?array $query = null, ?array $body = null): array;
}
