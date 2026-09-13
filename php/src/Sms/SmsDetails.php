<?php

declare(strict_types=1);

namespace Kalourmade\Arkesel\Sms;

final class SmsDetails
{
    public function __construct(
        public readonly string $id,
        public readonly DeliveryStatus $status,
        public readonly string $sender,
        public readonly string $recipient,
        public readonly string $message,
        public readonly int $messageCount,
        public readonly string $sentAtTime,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            status: DeliveryStatus::from($data['status']),
            sender: $data['sender'],
            recipient: $data['recipient'],
            message: $data['message'],
            messageCount: $data['message_count'],
            sentAtTime: $data['sent_at_time'],
        );
    }
}
