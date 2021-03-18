<?php

namespace Twrap;

use Psr\Http\Message\ServerRequestInterface;

interface TwrapClientInterface
{
    public function validateRequest(ServerRequestInterface $request): void;
}
