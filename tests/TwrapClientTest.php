<?php

declare(strict_types=1);

beforeEach(
    function () {
        $this->client = $this->getMockBuilder(\Twilio\Rest\Client::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->validator = $this->getMockBuilder(\Twilio\Security\RequestValidator::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->twrapClient = new \Twrap\TwrapClient($this->client, $this->validator);
    }
);

it('throws an exception when validating signature that has wrong uri and signature', function(){
    $request = $this->getMockBuilder(\Psr\Http\Message\ServerRequestInterface::class)
        ->getMock();

    $request->method('getHeaderLine')
        ->with('HTTP_X_TWILIO_SIGNATURE')
        ->willReturn('Slartibartfast');

    $request->method('getUri')
        ->willReturn('https://twilio.com');

    $this->validator->method('validate')
        ->willReturn(false);

    $this->twrapClient->validateRequest($request);
})->throws(\Twrap\Exception\InvalidSignatureException::class);
