<?php

declare(strict_types=1);

use Twrap\Exception\InvalidCredentialException;
use Twrap\Factory;

it('throws an exception with no passed account SID and no env var', function () {
    Factory::create();
})->throws(InvalidCredentialException::class);

it('throws an exception with no passed account secret and no env var', function () {
    Factory::create('Disaster Area');
})->throws(InvalidCredentialException::class);

it('creates a Client when given SID and secret', function(){
    $this->assertInstanceOf(
        \Twrap\TwrapClient::class, Factory::create('Disaster', 'Area')
    );
});
