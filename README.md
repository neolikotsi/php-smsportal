# PHP-SmsPortal
SMS Portal API for PHP

Send SMS from your PHP client API powered by [SMSPortal](https://www.smsportal.com/).

## Installation
Install the composer package `neolikotsi/php-smsportal`

```bash
composer required neolikotsi/php-smsportal
```

## Usage
Create an instance of `NeoLikotsi\SMSPortal\RestClient` and use it to hit the endpoints of the [SMSPortal RESTful API](https://docs.smsportal.com/docs/rest)

```php
use NeoLikotsi\SMSPortal\Message;
use NeoLikotsi\SMSPortal\RestClient;

$apiId = 'YOUR CLIENT API ID';
$apiSecret = 'YOUR CLIENT SECRET';
$baseRestUri = 'https://rest.smsportal.com/v1/';

$client = new RestClient($apiId, $apiSecret, $baseRestUri);
$message = new Message('Hello World');

// send to /bulkmessages endpoint
$responseArray1 = $client->message()->send([
                    'messages' => [
                        [
                            'destination' => '1234567890'
                            'content' => $message->getContent(),
                        ]
                    ]
                ]);

// send to /groupmessages endpoint
$responseArray2 = $client->message()->sendToGroup([
                    'message' => $message->getContent(),
                    'groups' => ['BloemfonteinStores', 'BotshabeloStores'],
                ]);
```

## Balance
You can check your SMS credit with the `balance` method.

```php
$client->balance();
```

## Test Mode ([see api doc](https://docs.smsportal.com/docs/rest-sending))
```php
$client->message()->inTestMode()->send([
    'messages' => [
        [
            'destination' => '1234567890'
            'content' => $message->getContent(),
        ]
    ]
]);
```

## For Laravel users see package
[neolikotsi/laravel-smsportal](https://github.com/neolikotsi/laravel-smsportal)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
