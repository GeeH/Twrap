<?php

declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Twrap\Exception\NoHeaderSignatureException;

beforeEach(function() {
    $this->client = $this->getMockBuilder(\Twrap\TwrapClientInterface::class)
        ->disableOriginalConstructor()
        ->getMock();
    $this->middleware = new Twrap\Middleware\TwilioWebhookMiddleware($this->client);
});

it('throws an exception if no signature header is found', function() {
    $requestMock = $this->getMockBuilder(ServerRequestInterface::class)
        ->getMock();
    $requestMock->expects($this->once())
        ->method('hasHeader')
        ->with('HTTP_X_TWILIO_SIGNATURE')
        ->willReturn(null);

    $handlerMock = $this->getMockBuilder(RequestHandlerInterface::class)
        ->getMock();

    $this->middleware->process($requestMock, $handlerMock);
})->throws(NoHeaderSignatureException::class);
