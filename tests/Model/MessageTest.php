<?php

use Brick\PhoneNumber\PhoneNumberParseException;
use Twrap\Model\Message;

it(
    'creates a fully hydrated message with DateTimeImmutable dates from good data',
    function () {
        $data =
            unserialize(
                'a:20:{s:4:"body";s:12:"Hello world!";s:11:"numSegments";s:1:"1";s:9:"direction";s:14:"outbound-reply";s:4:"from";s:13:"+447723427411";s:2:"to";s:13:"+447446188852";s:11:"dateUpdated";O:8:"DateTime":3:{s:4:"date";s:26:"2021-03-18 11:04:08.000000";s:13:"timezone_type";i:1;s:8:"timezone";s:6:"+00:00";}s:5:"price";s:8:"-0.04000";s:12:"errorMessage";N;s:3:"uri";s:104:"/2010-04-01/Accounts/ACb877821242bbaedc246328ca0a8c3fc6/Messages/SM0e3038708823b199692d85138d96e67c.json";s:10:"accountSid";s:34:"ACb877821242bbaedc246328ca0a8c3fc6";s:8:"numMedia";s:1:"0";s:6:"status";s:9:"delivered";s:19:"messagingServiceSid";N;s:3:"sid";s:34:"SM0e3038708823b199692d85138d96e67c";s:8:"dateSent";O:8:"DateTime":3:{s:4:"date";s:26:"2021-03-18 11:04:06.000000";s:13:"timezone_type";i:1;s:8:"timezone";s:6:"+00:00";}s:11:"dateCreated";O:8:"DateTime":3:{s:4:"date";s:26:"2021-03-18 11:04:06.000000";s:13:"timezone_type";i:1;s:8:"timezone";s:6:"+00:00";}s:9:"errorCode";N;s:9:"priceUnit";s:3:"USD";s:10:"apiVersion";s:10:"2010-04-01";s:15:"subresourceUris";a:2:{s:5:"media";s:110:"/2010-04-01/Accounts/ACb877821242bbaedc246328ca0a8c3fc6/Messages/SM0e3038708823b199692d85138d96e67c/Media.json";s:8:"feedback";s:113:"/2010-04-01/Accounts/ACb877821242bbaedc246328ca0a8c3fc6/Messages/SM0e3038708823b199692d85138d96e67c/Feedback.json";}}'
            );
        $message = Message::fromArray($data);
        $this->assertInstanceOf(DateTimeImmutable::class, $message->dateCreated);
        $this->assertInstanceOf(DateTimeImmutable::class, $message->dateSent);
        $this->assertInstanceOf(DateTimeImmutable::class, $message->dateUpdated);
    }
);

it(
    'creates a hydrated message with null dates from bad date data',
    function () {
        $data = unserialize(
            'a:20:{s:4:"body";s:12:"Hello world!";s:11:"numSegments";s:1:"1";s:9:"direction";s:14:"outbound-reply";s:4:"from";s:13:"+447723427411";s:2:"to";s:13:"+447446188852";s:11:"dateUpdated";N;s:5:"price";s:8:"-0.04000";s:12:"errorMessage";N;s:3:"uri";s:104:"/2010-04-01/Accounts/ACb877821242bbaedc246328ca0a8c3fc6/Messages/SM0e3038708823b199692d85138d96e67c.json";s:10:"accountSid";s:34:"ACb877821242bbaedc246328ca0a8c3fc6";s:8:"numMedia";s:1:"0";s:6:"status";s:9:"delivered";s:19:"messagingServiceSid";N;s:3:"sid";s:34:"SM0e3038708823b199692d85138d96e67c";s:8:"dateSent";N;s:11:"dateCreated";N;s:9:"errorCode";N;s:9:"priceUnit";s:3:"USD";s:10:"apiVersion";s:10:"2010-04-01";s:15:"subresourceUris";a:2:{s:5:"media";s:110:"/2010-04-01/Accounts/ACb877821242bbaedc246328ca0a8c3fc6/Messages/SM0e3038708823b199692d85138d96e67c/Media.json";s:8:"feedback";s:113:"/2010-04-01/Accounts/ACb877821242bbaedc246328ca0a8c3fc6/Messages/SM0e3038708823b199692d85138d96e67c/Feedback.json";}}'
        );
        $message = Message::fromArray($data);
        $this->assertNull($message->dateCreated);
        $this->assertNull($message->dateSent);
        $this->assertNull($message->dateUpdated);
    }
);

it(
    'throws an exception when the to phone number can\'t be parsed',
    function() {
        $data = unserialize(
            'a:20:{s:4:"body";s:12:"Hello world!";s:11:"numSegments";s:1:"1";s:9:"direction";s:14:"outbound-reply";s:4:"from";s:13:"+447723427411";s:2:"to";s:13:"+XXXXXXXXXXXX";s:11:"dateUpdated";N;s:5:"price";s:8:"-0.04000";s:12:"errorMessage";N;s:3:"uri";s:104:"/2010-04-01/Accounts/ACb877821242bbaedc246328ca0a8c3fc6/Messages/SM0e3038708823b199692d85138d96e67c.json";s:10:"accountSid";s:34:"ACb877821242bbaedc246328ca0a8c3fc6";s:8:"numMedia";s:1:"0";s:6:"status";s:9:"delivered";s:19:"messagingServiceSid";N;s:3:"sid";s:34:"SM0e3038708823b199692d85138d96e67c";s:8:"dateSent";N;s:11:"dateCreated";N;s:9:"errorCode";N;s:9:"priceUnit";s:3:"USD";s:10:"apiVersion";s:10:"2010-04-01";s:15:"subresourceUris";a:2:{s:5:"media";s:110:"/2010-04-01/Accounts/ACb877821242bbaedc246328ca0a8c3fc6/Messages/SM0e3038708823b199692d85138d96e67c/Media.json";s:8:"feedback";s:113:"/2010-04-01/Accounts/ACb877821242bbaedc246328ca0a8c3fc6/Messages/SM0e3038708823b199692d85138d96e67c/Feedback.json";}}'
        );
        Message::fromArray($data);
    }
)->throws(PhoneNumberParseException::class);

it(
    'throws an exception when the from phone number can\'t be parsed',
    function() {
        $data = unserialize(
            'a:20:{s:4:"body";s:12:"Hello world!";s:11:"numSegments";s:1:"1";s:9:"direction";s:14:"outbound-reply";s:4:"from";s:13:"+XXXXXXXXXXXX";s:2:"to";s:13:"+447446188852";s:11:"dateUpdated";N;s:5:"price";s:8:"-0.04000";s:12:"errorMessage";N;s:3:"uri";s:104:"/2010-04-01/Accounts/ACb877821242bbaedc246328ca0a8c3fc6/Messages/SM0e3038708823b199692d85138d96e67c.json";s:10:"accountSid";s:34:"ACb877821242bbaedc246328ca0a8c3fc6";s:8:"numMedia";s:1:"0";s:6:"status";s:9:"delivered";s:19:"messagingServiceSid";N;s:3:"sid";s:34:"SM0e3038708823b199692d85138d96e67c";s:8:"dateSent";N;s:11:"dateCreated";N;s:9:"errorCode";N;s:9:"priceUnit";s:3:"USD";s:10:"apiVersion";s:10:"2010-04-01";s:15:"subresourceUris";a:2:{s:5:"media";s:110:"/2010-04-01/Accounts/ACb877821242bbaedc246328ca0a8c3fc6/Messages/SM0e3038708823b199692d85138d96e67c/Media.json";s:8:"feedback";s:113:"/2010-04-01/Accounts/ACb877821242bbaedc246328ca0a8c3fc6/Messages/SM0e3038708823b199692d85138d96e67c/Feedback.json";}}'
        );
        Message::fromArray($data);
    }
)->throws(PhoneNumberParseException::class);
