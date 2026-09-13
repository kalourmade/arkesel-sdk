<?php

require __DIR__ . '/../../vendor/autoload.php';

use Kalourmade\Arkesel\ArkeselClient;

$groupName = $argv[1] ?? throw new RuntimeException('Usage: php send_to_group.php <group_name>');
$client = new ArkeselClient(getenv('ARKESEL_API_KEY') ?: throw new RuntimeException('Set ARKESEL_API_KEY'));

$client->sms->sendToGroup('Arkesel', $groupName, 'Hello group, from the Arkesel PHP SDK');

printf("Sent to group '%s'\n", $groupName);
