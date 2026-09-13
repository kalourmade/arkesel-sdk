<?php

declare(strict_types=1);

namespace Kalourmade\Arkesel\Sms;

use Kalourmade\Arkesel\Http\ErrorMapper;
use Kalourmade\Arkesel\Http\HttpTransport;

final class SmsResource
{
    public function __construct(private readonly HttpTransport $transport)
    {
    }

    /**
     * @param string[] $recipients
     */
    public function send(
        string $sender,
        array $recipients,
        string $message,
        ?string $callbackUrl = null,
        ?string $scheduledDate = null,
        ?string $useCase = null,
        ?bool $sandbox = null,
    ): SendResponse {
        $body = ['sender' => $sender, 'recipients' => $recipients, 'message' => $message];
        if ($callbackUrl !== null) {
            $body['callback_url'] = $callbackUrl;
        }
        if ($scheduledDate !== null) {
            $body['scheduled_date'] = $scheduledDate;
        }
        if ($useCase !== null) {
            $body['use_case'] = $useCase;
        }
        if ($sandbox !== null) {
            $body['sandbox'] = $sandbox;
        }

        $response = $this->transport->request('POST', '/api/v2/sms/send', body: $body);
        ErrorMapper::throwIfError($response['status'], $response['body']);

        return SendResponse::fromArray(json_decode($response['body'], true, flags: JSON_THROW_ON_ERROR));
    }

    public function get(string $id): SmsDetails
    {
        $response = $this->transport->request('GET', "/api/v2/sms/{$id}");
        ErrorMapper::throwIfError($response['status'], $response['body']);

        $decoded = json_decode($response['body'], true, flags: JSON_THROW_ON_ERROR);

        return SmsDetails::fromArray($decoded['data']);
    }

    /**
     * @param string[] $ids
     * @return array<string, ReportEntry>
     */
    public function messageReports(array $ids): array
    {
        $response = $this->transport->request('POST', '/api/v2/sms/message-reports', body: ['msg_ids' => $ids]);
        ErrorMapper::throwIfError($response['status'], $response['body']);

        $decoded = json_decode($response['body'], true, flags: JSON_THROW_ON_ERROR);

        $reports = [];
        foreach ($decoded['data'] as $id => $entry) {
            $reports[$id] = ReportEntry::fromArray($entry);
        }

        return $reports;
    }

    public function sendToGroup(string $sender, string $groupName, string $message): void
    {
        $response = $this->transport->request('POST', '/api/v2/sms/send/contact-group', body: [
            'sender' => $sender,
            'group_name' => $groupName,
            'message' => $message,
        ]);
        ErrorMapper::throwIfError($response['status'], $response['body']);
    }
}
