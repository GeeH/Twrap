<?php

declare(strict_types=1);

use Brick\PhoneNumber\PhoneNumber;
use Twilio\Rest\Api\V2010\Account\MessageContext;
use Twilio\Rest\Api\V2010\Account\MessageInstance;
use Twrap\Exception\MessageNotFoundException;
use Twrap\Model\Message;

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

it(
    'throws an exception when the message does not exist',
    function () {
        $messageSid = 'ARTHURD3N7';
        $this->client->expects($this->once())
            ->method('__call')
            ->with('messages', [$messageSid])
            ->willReturn(null);

        $this->twrapClient->messageDetails($messageSid);
    }
)->throws(MessageNotFoundException::class);

it(
    'returns a message from the API when passed a valid SID',
    function () {
        $messageSid = 'ARTHURD3N7';
        $messageArray = unserialize(
            'a:20:{s:4:"body";s:12:"Hello world!";s:11:"numSegments";s:1:"1";s:9:"direction";s:14:"outbound-reply";s:4:"from";s:13:"+447723427411";s:2:"to";s:13:"+447446188852";s:11:"dateUpdated";O:8:"DateTime":3:{s:4:"date";s:26:"2021-03-18 11:04:08.000000";s:13:"timezone_type";i:1;s:8:"timezone";s:6:"+00:00";}s:5:"price";s:8:"-0.04000";s:12:"errorMessage";N;s:3:"uri";s:104:"/2010-04-01/Accounts/ACb877821242bbaedc246328ca0a8c3fc6/Messages/SM0e3038708823b199692d85138d96e67c.json";s:10:"accountSid";s:34:"ACb877821242bbaedc246328ca0a8c3fc6";s:8:"numMedia";s:1:"0";s:6:"status";s:9:"delivered";s:19:"messagingServiceSid";N;s:3:"sid";s:34:"SM0e3038708823b199692d85138d96e67c";s:8:"dateSent";O:8:"DateTime":3:{s:4:"date";s:26:"2021-03-18 11:04:06.000000";s:13:"timezone_type";i:1;s:8:"timezone";s:6:"+00:00";}s:11:"dateCreated";O:8:"DateTime":3:{s:4:"date";s:26:"2021-03-18 11:04:06.000000";s:13:"timezone_type";i:1;s:8:"timezone";s:6:"+00:00";}s:9:"errorCode";N;s:9:"priceUnit";s:3:"USD";s:10:"apiVersion";s:10:"2010-04-01";s:15:"subresourceUris";a:2:{s:5:"media";s:110:"/2010-04-01/Accounts/ACb877821242bbaedc246328ca0a8c3fc6/Messages/SM0e3038708823b199692d85138d96e67c/Media.json";s:8:"feedback";s:113:"/2010-04-01/Accounts/ACb877821242bbaedc246328ca0a8c3fc6/Messages/SM0e3038708823b199692d85138d96e67c/Feedback.json";}}'
        );

        $messageInstance = $this->getMockBuilder(MessageInstance::class)
            ->disableOriginalConstructor()
            ->getMock();
        $messageInstance->expects($this->once())
            ->method('toArray')
            ->willReturn($messageArray);

        $messageContext = $this->getMockBuilder(MessageContext::class)
            ->disableOriginalConstructor()
            ->getMock();
        $messageContext->expects($this->once())
            ->method('fetch')
            ->willReturn($messageInstance);

        $this->client->expects($this->once())
            ->method('__call')
            ->with('messages', [$messageSid])
            ->willReturn($messageContext);

        $this::assertInstanceOf(
            Message::class,
            $this->twrapClient->messageDetails($messageSid)
        );
    }
);

it(
    'returns an empty array when no messages are returned',
    function () {
        $phoneNumberString = '+44123456789';

        $this->client->messages = $this->getMockBuilder(\Twilio\Rest\Api\V2010\Account\MessageList::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->client->messages
            ->expects($this->once())
            ->method('read')
            ->with(['to' => $phoneNumberString])
            ->willReturn([]);

        $this::assertSame([], $this->twrapClient->messagesTo(PhoneNumber::parse($phoneNumberString)));
    }
);

it(
    'returns an array of hydrated messages when messages are returned',
    function () {
        $phoneNumberString = '+44123456789';

        $this->client->messages = $this->getMockBuilder(\Twilio\Rest\Api\V2010\Account\MessageList::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->client->messages
            ->expects($this->once())
            ->method('read')
            ->with(['to' => $phoneNumberString])
            ->willReturn(include 'assets/serializedMessages.php');

        $this::assertInstanceOf(
            Message::class,
            $this->twrapClient->messagesTo(PhoneNumber::parse($phoneNumberString))[0]
        );
    }
);
