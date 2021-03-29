# TWRAP
- a convenience wrapper for the Twilio PHP library

![example workflow](https://github.com/GeeH/Twrap/actions/workflows/ci.yml/badge.svg)

---

This is a convenience wrapper for the Twilio SDK library because everyone hates working with 
arrays, and because I got sick of writing middleware to protect incoming webhooks. The aim is
to slowly wrap the generated SDK and return objects wherever possible, starting with the most
used endpoints.

---

## Creating a Client

You can use the factory method to create a new instance of the `TwrapClient`:

```php
$client = \Twrap\Factory::create('MYACCOUNTSID', 'MYSUPERSECRETAUTHTOKEN');
```

## Using the Client

Once you have a client, you can use the lovely methods to get objects back from the API,
for example to lookup the details of a messages:

```php
var_dump($client->messageDetails('SM322bd06e7c948e05461cc5c5dea3797b'));
```

```php
class Twrap\Model\Message#11 (20) {
  public string $body =>
  string(45) "Hello, drop me an email at ghockin@twilio.com"
  public int $numSegments =>
  int(1)
  public string $direction =>
  string(14) "outbound-reply"
  public string $from =>
  string(13) "+447123456789"
  public string $to =>
  string(13) "+447987654321"
  public ?DateTimeImmutable $dateUpdated =>
  class DateTimeImmutable#12 (3) {
    public $date =>
    string(26) "2021-03-22 16:23:25.000000"
    public $timezone_type =>
    int(1)
    public $timezone =>
    string(6) "+00:00"
  }
  public string $price =>
  string(8) "-0.04000"
  public ?string $errorMessage =>
  string(31) "Unreachable destination handset"
  public string $uri =>
  string(104) "/2010-04-01/Accounts/ACb877821242bbaedc246328ca0a8c3fc6/Messages/SM322bd06e7c948e05461cc5c5dea3797b.json"
  public string $accountSid =>
  string(34) "ACb877821242bbaedc246328ca0a8c3fc6"
  public int $numMedia =>
  int(0)
  public string $status =>
  string(11) "undelivered"
  public ?string $messagingServiceSid =>
  NULL
  public string $sid =>
  string(34) "SM322bd06e7c948e05461cc5c5dea3797b"
  public ?DateTimeImmutable $dateSent =>
  class DateTimeImmutable#17 (3) {
    public $date =>
    string(26) "2021-03-20 15:18:11.000000"
    public $timezone_type =>
    int(1)
    public $timezone =>
    string(6) "+00:00"
  }
  public ?DateTimeImmutable $dateCreated =>
  class DateTimeImmutable#18 (3) {
    public $date =>
    string(26) "2021-03-20 15:18:11.000000"
    public $timezone_type =>
    int(1)
    public $timezone =>
    string(6) "+00:00"
  }
  public ?string $errorCode =>
  string(5) "30003"
  public string $priceUnit =>
  string(3) "USD"
  public string $apiVersion =>
  string(10) "2010-04-01"
  public array $subresourceUris =>
  array(2) {
    'media' =>
    string(110) "/2010-04-01/Accounts/ACb877821242bbaedc246328ca0a8c3fc6/Messages/SM322bd06e7c948e05461cc5c5dea3797b/Media.json"
    'feedback' =>
    string(113) "/2010-04-01/Accounts/ACb877821242bbaedc246328ca0a8c3fc6/Messages/SM322bd06e7c948e05461cc5c5dea3797b/Feedback.json"
  }
} 
```

## Middleware

A [PSR-15](https://www.php-fig.org/psr/psr-15/) middleware is available that automatically checks the 
Twilio security header for any requests that have it added. This prevents spoofed requests from Twilio
and protects your webhooks from being called by anyone unexpected. You can add it anywhere you can use
PSR-15 middleware. For example, in [Slim Framework](https://www.slimframework.com/):

```php
<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__.'/../vendor/autoload.php';

$app = AppFactory::create();

$client = \Twrap\Factory::create(getenv('TWILIO_DEMO_ACCOUNT_SID'), getenv('TWILIO_DEMO_AUTH_TOKEN'));
$middleware = new \Twrap\Middleware\TwilioWebhookMiddleware($client);

$app->post(
    '/',
    function (Request $request, Response $response, $args) {
        $response->getBody()->write("Hello world!");
        $response = $response->withAddedHeader('Content-Type', 'text/plain');
        $response = $response->withStatus(200);
        return $response;
    }
)->addMiddleware($middleware);

$app->run();
```

## Contributing

Please feel free to contribute, either by letting me know which methods would be most useful to wrap 
next, writing code, filing bugs, or by shouting encouragement to me on Twitter at [@GeeH](https://twitter.com/GeeH).
