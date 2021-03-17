<?php

namespace Twrap;

use Psr\Http\Message\ServerRequestInterface;
use Twilio\Rest\Client as TwilioClient;
use Twilio\Security\RequestValidator;

interface TwrapClientInterface
{
    public function __construct(TwilioClient $client, RequestValidator $requestValidator);
    public function validateRequest(ServerRequestInterface $request): void;
}
