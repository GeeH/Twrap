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
        ->with('X-TWILIO-SIGNATURE')
        ->willReturn(false);

    $handlerMock = $this->getMockBuilder(RequestHandlerInterface::class)
        ->getMock();

    $this->middleware->process($requestMock, $handlerMock);
})->throws(NoHeaderSignatureException::class);

it('runs the handler if the signatures match', function() {
    $requestMock = $this->getMockBuilder(ServerRequestInterface::class)
        ->getMock();
    $requestMock->expects($this->once())
        ->method('hasHeader')
        ->with('X-TWILIO-SIGNATURE')
        ->willReturn(true);


    $handler = $this->getMockBuilder(RequestHandlerInterface::class)
        ->getMock();
    $handler->expects($this->once())
        ->method('handle')
        ->with($requestMock);

    $this->middleware->process($requestMock, $handler);
});
