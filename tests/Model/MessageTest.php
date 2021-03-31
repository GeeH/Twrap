<?php

use Brick\PhoneNumber\PhoneNumberParseException;
use Twrap\Model\Message;

it(
    'creates a fully hydrated message with DateTimeImmutable dates from good data',
    function () {
        $data = [
            'body' => 'Hello world!',
            'numSegments' => '1',
            'direction' => 'outbound-reply',
            'from' => '+447723427411',
            'to' => '+447123456782',
            'dateUpdated' =>
                DateTime::__set_state(
                    [
                        'date' => '2021-03-18 11:04:08.000000',
                        'timezone_type' => 1,
                        'timezone' => '+00:00',
                    ]
                ),
            'price' => '-0.04000',
            'errorMessage' => null,
            'uri' => '/2010-04-01/Accounts/ACb877821242bbaedc246328ca0a8c3fc6/Messages/SM0e3038708823b199692d85138d96e67c.json',
            'accountSid' => 'ACb877821242bbaedc246328ca0a8c3fc6',
            'numMedia' => '0',
            'status' => 'delivered',
            'messagingServiceSid' => null,
            'sid' => 'SM0e3038708823b199692d85138d96e67c',
            'dateSent' =>
                DateTime::__set_state(
                    [
                        'date' => '2021-03-18 11:04:06.000000',
                        'timezone_type' => 1,
                        'timezone' => '+00:00',
                    ]
                ),
            'dateCreated' =>
                DateTime::__set_state(
                    [
                        'date' => '2021-03-18 11:04:06.000000',
                        'timezone_type' => 1,
                        'timezone' => '+00:00',
                    ]
                ),
            'errorCode' => null,
            'priceUnit' => 'USD',
            'apiVersion' => '2010-04-01',
            'subresourceUris' =>
                [
                    'media' => '/2010-04-01/Accounts/ACb877821242bbaedc246328ca0a8c3fc6/Messages/SM0e3038708823b199692d85138d96e67c/Media.json',
                    'feedback' => '/2010-04-01/Accounts/ACb877821242bbaedc246328ca0a8c3fc6/Messages/SM0e3038708823b199692d85138d96e67c/Feedback.json',
                ],
        ];

        $message = Message::fromArray($data);
        $this->assertInstanceOf(DateTimeImmutable::class, $message->dateCreated);
        $this->assertInstanceOf(DateTimeImmutable::class, $message->dateSent);
        $this->assertInstanceOf(DateTimeImmutable::class, $message->dateUpdated);
    }
);

it(
    'creates a hydrated message with null dates from bad date data',
    function () {
        $data = [
            'body' => 'Hello world!',
            'numSegments' => '1',
            'direction' => 'outbound-reply',
            'from' => '+447723427411',
            'to' => '+447446188852',
            'dateUpdated' => null,
            'price' => '-0.04000',
            'errorMessage' => null,
            'uri' => '/2010-04-01/Accounts/ACb877821242bbaedc246328ca0a8c3fc6/Messages/SM0e3038708823b199692d85138d96e67c.json',
            'accountSid' => 'ACb877821242bbaedc246328ca0a8c3fc6',
            'numMedia' => '0',
            'status' => 'delivered',
            'messagingServiceSid' => null,
            'sid' => 'SM0e3038708823b199692d85138d96e67c',
            'dateSent' => null,
            'dateCreated' => null,
            'errorCode' => null,
            'priceUnit' => 'USD',
            'apiVersion' => '2010-04-01',
            'subresourceUris' =>
                [
                    'media' => '/2010-04-01/Accounts/ACb877821242bbaedc246328ca0a8c3fc6/Messages/SM0e3038708823b199692d85138d96e67c/Media.json',
                    'feedback' => '/2010-04-01/Accounts/ACb877821242bbaedc246328ca0a8c3fc6/Messages/SM0e3038708823b199692d85138d96e67c/Feedback.json',
                ],
        ];

        $message = Message::fromArray($data);
        $this->assertNull($message->dateCreated);
        $this->assertNull($message->dateSent);
        $this->assertNull($message->dateUpdated);
    }
);

it(
    'throws an exception when the to phone number can\'t be parsed',
    function () {
        $data = [
            'body' => 'Hello world!',
            'numSegments' => '1',
            'direction' => 'outbound-reply',
            'from' => '+447723427411',
            'to' => '+XXXXXXXXXXXX',
            'dateUpdated' => null,
            'price' => '-0.04000',
            'errorMessage' => null,
            'uri' => '/2010-04-01/Accounts/ACb877821242bbaedc246328ca0a8c3fc6/Messages/SM0e3038708823b199692d85138d96e67c.json',
            'accountSid' => 'ACb877821242bbaedc246328ca0a8c3fc6',
            'numMedia' => '0',
            'status' => 'delivered',
            'messagingServiceSid' => null,
            'sid' => 'SM0e3038708823b199692d85138d96e67c',
            'dateSent' => null,
            'dateCreated' => null,
            'errorCode' => null,
            'priceUnit' => 'USD',
            'apiVersion' => '2010-04-01',
            'subresourceUris' =>
                [
                    'media' => '/2010-04-01/Accounts/ACb877821242bbaedc246328ca0a8c3fc6/Messages/SM0e3038708823b199692d85138d96e67c/Media.json',
                    'feedback' => '/2010-04-01/Accounts/ACb877821242bbaedc246328ca0a8c3fc6/Messages/SM0e3038708823b199692d85138d96e67c/Feedback.json',
                ],
        ];

        Message::fromArray($data);
    }
)->throws(PhoneNumberParseException::class);

it(
    'throws an exception when the from phone number can\'t be parsed',
    function () {
        $data = [
            'body' => 'Hello world!',
            'numSegments' => '1',
            'direction' => 'outbound-reply',
            'from' => '+XXXXXXXXXXXX',
            'to' => '+447446188852',
            'dateUpdated' => null,
            'price' => '-0.04000',
            'errorMessage' => null,
            'uri' => '/2010-04-01/Accounts/ACb877821242bbaedc246328ca0a8c3fc6/Messages/SM0e3038708823b199692d85138d96e67c.json',
            'accountSid' => 'ACb877821242bbaedc246328ca0a8c3fc6',
            'numMedia' => '0',
            'status' => 'delivered',
            'messagingServiceSid' => null,
            'sid' => 'SM0e3038708823b199692d85138d96e67c',
            'dateSent' => null,
            'dateCreated' => null,
            'errorCode' => null,
            'priceUnit' => 'USD',
            'apiVersion' => '2010-04-01',
            'subresourceUris' =>
                [
                    'media' => '/2010-04-01/Accounts/ACb877821242bbaedc246328ca0a8c3fc6/Messages/SM0e3038708823b199692d85138d96e67c/Media.json',
                    'feedback' => '/2010-04-01/Accounts/ACb877821242bbaedc246328ca0a8c3fc6/Messages/SM0e3038708823b199692d85138d96e67c/Feedback.json',
                ],
        ];

        Message::fromArray($data);
    }
)->throws(PhoneNumberParseException::class);
