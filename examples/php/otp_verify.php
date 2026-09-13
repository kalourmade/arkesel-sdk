<?php

require __DIR__ . '/../../vendor/autoload.php';

use Kalourmade\Arkesel\ArkeselClient;

$number = $argv[1] ?? throw new RuntimeException('Usage: php otp_verify.php <number> <code>');
$code = $argv[2] ?? throw new RuntimeException('Usage: php otp_verify.php <number> <code>');
$client = new ArkeselClient(getenv('ARKESEL_API_KEY') ?: throw new RuntimeException('Set ARKESEL_API_KEY'));

$result = $client->otp->verify($code, $number);

printf("Verified: %s\n", $result->message);
