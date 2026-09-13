# Arkesel PHP SDK

`kalourmade/arkesel-sms` — PHP SDK for the Arkesel SMS/OTP API.

## Install

```bash
composer require kalourmade/arkesel-sms
```

Requires PHP 8.1+. No runtime dependencies.

## Quickstart

```php
use Kalourmade\Arkesel\ArkeselClient;

$client = new ArkeselClient(getenv('ARKESEL_API_KEY'));

$response = $client->sms->send('Arkesel', ['233544919953'], 'Hello from Arkesel');

foreach ($response->results as $result) {
    echo "{$result->recipient} -> {$result->id}\n";
}
```

Full runnable examples: [`examples/php/send.php`](../examples/php/send.php),
[`get.php`](../examples/php/get.php), [`message_reports.php`](../examples/php/message_reports.php),
[`create_group.php`](../examples/php/create_group.php), [`send_to_group.php`](../examples/php/send_to_group.php),
[`otp_generate.php`](../examples/php/otp_generate.php), [`otp_verify.php`](../examples/php/otp_verify.php).

## Error handling

```php
use Kalourmade\Arkesel\Exceptions\{AuthenticationError, InsufficientBalanceError, ValidationError, ApiError, NetworkError};

try {
    $client->sms->send('Arkesel', ['233544919953'], 'Hi');
} catch (AuthenticationError $e) {
    // bad/missing api-key, or inactive gateway
} catch (InsufficientBalanceError $e) {
    // top up your Arkesel balance
} catch (ValidationError $e) {
    // bad request payload
} catch (ApiError $e) {
    // other API error — $e->statusCode, $e->rawBody
} catch (NetworkError $e) {
    // couldn't reach Arkesel
}
```

## Parsing delivery callbacks

Arkesel has no signature scheme for its delivery callback — this
parser validates shape only, it does not authenticate the request:

```php
use Kalourmade\Arkesel\DeliveryCallback;

$result = DeliveryCallback::parse($_GET);
// $result['smsId'], $result['status']
```

Full example: [`examples/php/parse_delivery_callback.php`](../examples/php/parse_delivery_callback.php).
