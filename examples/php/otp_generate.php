<?php

require __DIR__ . '/../../vendor/autoload.php';

use Kalourmade\Arkesel\ArkeselClient;

$number = $argv[1] ?? throw new RuntimeException('Usage: php otp_generate.php <number>');
$client = new ArkeselClient(getenv('ARKESEL_API_KEY') ?: throw new RuntimeException('Set ARKESEL_API_KEY'));

$result = $client->otp->generate(
    expiry: 5,
    length: 6,
    medium: 'sms',
    message: 'Your Arkesel PHP SDK test code is %otp_code%',
    number: $number,
    senderId: 'Arkesel',
    type: 'numeric'
);

printf("Generated: %s\n", $result->message);
