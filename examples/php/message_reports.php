<?php

require __DIR__ . '/../../vendor/autoload.php';

use Kalourmade\Arkesel\ArkeselClient;

$ids = array_slice($argv, 1) ?: throw new RuntimeException('Usage: php message_reports.php <id> [id...]');
$client = new ArkeselClient(getenv('ARKESEL_API_KEY') ?: throw new RuntimeException('Set ARKESEL_API_KEY'));

$reports = $client->sms->messageReports($ids);

foreach ($reports as $id => $report) {
    if ($report->error !== null) {
        printf("%s — error: %s\n", $id, $report->error);
    } else {
        printf("%s — %s (%s)\n", $id, $report->message, $report->status->value);
    }
}
