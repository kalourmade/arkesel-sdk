<?php

require __DIR__ . '/../../vendor/autoload.php';

use Kalourmade\Arkesel\ArkeselClient;

$id = $argv[1] ?? throw new RuntimeException('Usage: php get.php <message_id>');
$client = new ArkeselClient(getenv('ARKESEL_API_KEY') ?: throw new RuntimeException('Set ARKESEL_API_KEY'));

$details = $client->sms->get($id);

printf("%s — %s (%s)\n", $details->id, $details->message, $details->status->value);
