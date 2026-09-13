<?php

require __DIR__ . '/../../vendor/autoload.php';

use Kalourmade\Arkesel\DeliveryCallback;
use Kalourmade\Arkesel\Exceptions\ValidationError;

// Simulate an incoming GET callback for demonstration purposes.
$query = ['sms_id' => 'abc-123', 'status' => 'DELIVERED'];

$result = DeliveryCallback::parse($query);
printf("sms_id=%s status=%s\n", $result['smsId'], $result['status']);

// Demonstrate the failure case: a malformed callback is rejected.
try {
    DeliveryCallback::parse(['sms_id' => 'abc-123']);
} catch (ValidationError $e) {
    printf("Correctly rejected malformed callback: %s\n", $e->getMessage());
}
