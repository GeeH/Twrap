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

it(
    'throws an exception when validating signature that has wrong uri and signature',
    function () {
        $request = getValidRequestMock();

        $this->validator->method('validate')
            ->willReturn(false);

        $this->twrapClient->validateRequest($request);
    }
)->throws(\Twrap\Exception\InvalidSignatureException::class);

it(
    'uses the querystring parameters if the request is GET',
    function () {
        $parameters = ['drink' => 'Pan-Galactic Gargle Blaster'];

        $request = getValidRequestMock();
        $request->method('getMethod')
            ->willReturn('GET');

        $request->expects($this->once())
            ->method('getQueryParams')
            ->willReturn($parameters);

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->with('Slartibartfast', 'https://twilio.com', $parameters)
            ->willReturn(true);

        $this->twrapClient->validateRequest($request);
    }
);

it(
    'uses the body parameters if the request is POST',
    function () {
        $parameters = ['drink' => 'Pan-Galactic Gargle Blaster'];

        $request = getValidRequestMock();
        $request->method('getMethod')
            ->willReturn('POST');

        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn($parameters);

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->with('Slartibartfast', 'https://twilio.com', $parameters)
            ->willReturn(true);

        $this->twrapClient->validateRequest($request);
    }
);
