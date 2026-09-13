<?php

declare(strict_types=1);

namespace Kalourmade\Arkesel\Sms;

final class SendResponse
{
    /**
     * @param SendResultEntry[] $results
     * @param string[] $invalidNumbers
     */
    public function __construct(
        public readonly array $results,
        public readonly array $invalidNumbers,
        public readonly ?string $message,
    ) {
    }

    public static function fromArray(array $data): self
    {
        $results = [];
        $invalidNumbers = [];

        foreach ($data['data'] ?? [] as $entry) {
            if (isset($entry['recipient'], $entry['id'])) {
                $results[] = new SendResultEntry($entry['recipient'], $entry['id']);
            } elseif (isset($entry['invalid numbers'])) {
                array_push($invalidNumbers, ...$entry['invalid numbers']);
            }
        }

        return new self($results, $invalidNumbers, $data['message'] ?? null);
    }
}
