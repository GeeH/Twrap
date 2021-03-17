<?php

declare(strict_types=1);

namespace Twrap;

use Psr\Http\Message\ServerRequestInterface;
use Twilio\Rest\Client as TwilioClient;
use Twilio\Security\RequestValidator;
use Twrap\Exception\InvalidSignatureException;

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
            $request->getHeaderLine('HTTP_X_TWILIO_SIGNATURE'),
            (string)$request->getUri(),
            $data,
        )) {
            throw new InvalidSignatureException('Security signatures do not match');
        }
    }
}