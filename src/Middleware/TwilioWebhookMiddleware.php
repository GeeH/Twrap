<?php

declare(strict_types=1);

namespace Twrap\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Twrap\Exception\NoHeaderSignatureException;
use Twrap\TwrapClientInterface;

class TwilioWebhookMiddleware implements MiddlewareInterface
{
    public function __construct(private TwrapClientInterface $client) { }

    public const HEADER_NAME = 'X-TWILIO-SIGNATURE';

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!$request->hasHeader(self::HEADER_NAME)) {
            throw new NoHeaderSignatureException(self::HEADER_NAME . ' is not found');
        }

        $this->client->validateRequest($request);
        return $handler->handle($request);
    }
}
