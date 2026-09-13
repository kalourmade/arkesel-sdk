<?php

declare(strict_types=1);

namespace Kalourmade\Arkesel\Tests\Fakes;

use Kalourmade\Arkesel\Http\HttpTransport;

final class FakeHttpTransport implements HttpTransport
{
    /** @var array{status:int, body:string}[] */
    private array $responses;

    /** @var array{method:string, path:string, query:?array, body:?array}[] */
    public array $requests = [];

    /**
     * @param array{status:int, body:string}[] $responses
     */
    public function __construct(array $responses)
    {
        $this->responses = $responses;
    }

    public function request(string $method, string $path, ?array $query = null, ?array $body = null): array
    {
        $this->requests[] = ['method' => $method, 'path' => $path, 'query' => $query, 'body' => $body];

        return array_shift($this->responses);
    }
}
