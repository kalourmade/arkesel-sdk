<?php

require __DIR__ . '/../../vendor/autoload.php';

use Kalourmade\Arkesel\ArkeselClient;

$client = new ArkeselClient(getenv('ARKESEL_API_KEY') ?: throw new RuntimeException('Set ARKESEL_API_KEY'));

$response = $client->sms->send('Arkesel', ['233544919953'], 'Hello from the Arkesel PHP SDK');

foreach ($response->results as $result) {
    printf("Sent to %s — id=%s\n", $result->recipient, $result->id);
}
foreach ($response->invalidNumbers as $invalid) {
    printf("Invalid number: %s\n", $invalid);
}
