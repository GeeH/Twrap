<?php

declare(strict_types=1);

namespace Twrap;


use Twilio\Rest\Client;
use Twilio\Security\RequestValidator;
use Twrap\TwrapClient as TwrapClient;

final class Factory
{
    public static function create(string $accountSid = null, string $authToken = null): TwrapClient
    {
        $accountSid = $accountSid ?? getenv('TWILIO_ACCOUNT_SID');
        $authToken = $authToken ?? getenv('TWILIO_AUTH_TOKEN');

        if (!$accountSid || !$authToken) {
            throw new Exception\InvalidCredentialException('No account SID or auth token passed into the factory');
        }

        $client = new Client($accountSid, $authToken);
        return new TwrapClient($client, new RequestValidator($authToken));
    }
}
