<?php

declare(strict_types=1);

namespace Kalourmade\Arkesel\Sms;

final class ReportEntry
{
    public function __construct(
        public readonly ?DeliveryStatus $status,
        public readonly ?string $sender,
        public readonly ?string $recipient,
        public readonly ?string $message,
        public readonly ?int $messageCount,
        public readonly ?string $sentAtTime,
        public readonly ?string $error,
    ) {
    }

    public static function fromArray(array $data): self
    {
        if (($data['status'] ?? null) === 'error') {
            return new self(null, null, null, null, null, null, $data['response']);
        }

        return new self(
            status: DeliveryStatus::from($data['status']),
            sender: $data['sender'],
            recipient: $data['recipient'],
            message: $data['message'],
            messageCount: $data['message_count'],
            sentAtTime: $data['sent_at_time'],
            error: null,
        );
    }
}
