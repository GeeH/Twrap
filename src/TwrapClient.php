<?php

declare(strict_types=1);

namespace Twrap;

use Psr\Http\Message\ServerRequestInterface;
use Twilio\Rest\Client as TwilioClient;
use Twilio\Security\RequestValidator;
use Twrap\Exception\InvalidSignatureException;
use Twrap\Exception\MessageNotFoundException;
use Twrap\Middleware\TwilioWebhookMiddleware;
use Twrap\Model\Message;

final class TwrapClient implements TwrapClientInterface
{
    public function __construct(private TwilioClient $client, private RequestValidator $validator) { }

    public function validateRequest(ServerRequestInterface $request): void
    {
        $data = [];

        if ($request->getMethod() === 'POST') {
            $data = (array)$request->getParsedBody();
        }

        if ($request->getMethod() === 'GET') {
            $data = $request->getQueryParams();
        }

        if (!$this->validator->validate(
            $request->getHeaderLine(TwilioWebhookMiddleware::HEADER_NAME),
            (string)$request->getUri(),
            $data,
        )) {
            throw new InvalidSignatureException('Security signatures do not match');
        }
    }

    public function getMessageDetails(string $messageSid): Message
    {
        if (!$message = $this->client->messages($messageSid)) {
            throw new MessageNotFoundException("Message with SID {$messageSid} not found");
        }

        $message = $message->fetch()->toArray();
        return Message::fromArray($message);
    }
}
