<?php

require __DIR__ . '/../../vendor/autoload.php';

use Kalourmade\Arkesel\ArkeselClient;

$groupName = $argv[1] ?? throw new RuntimeException('Usage: php create_group.php <group_name>');
$client = new ArkeselClient(getenv('ARKESEL_API_KEY') ?: throw new RuntimeException('Set ARKESEL_API_KEY'));

$client->groups->create($groupName);
$client->groups->addContacts($groupName, [
    ['phone_number' => '233544919953', 'first_name' => 'Arkesel', 'last_name' => 'Dev'],
]);

printf("Created group '%s' with 1 contact\n", $groupName);
