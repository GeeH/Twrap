<?php

declare(strict_types=1);

namespace Twrap\Model;

use Brick\PhoneNumber\PhoneNumber;

class Message
{
    private function __construct(
        public string $body,
        public int $numSegments,
        public string $direction,
        public PhoneNumber $from,
        public PhoneNumber $to,
        public ?\DateTimeImmutable $dateUpdated,
        public string $price,
        public ?string $errorMessage,
        public string $uri,
        public string $accountSid,
        public int $numMedia,
        public string $status,
        public ?string $messagingServiceSid,
        public string $sid,
        public ?\DateTimeImmutable $dateSent,
        public ?\DateTimeImmutable $dateCreated,
        public ?string $errorCode,
        public string $priceUnit,
        public string $apiVersion,
        public array $subresourceUris
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            (string)$data['body'],
            (int)$data['numSegments'],
            (string)$data['direction'],
            PhoneNumber::parse((string)$data['from']),
            PhoneNumber::parse((string)$data['to']),
            ($data['dateUpdated'] instanceof \DateTime)
                ? \DateTimeImmutable::createFromMutable($data['dateUpdated'])
                : null,
            (string)$data['price'],
            $data['errorMessage'] ? (string)$data['errorMessage'] : null,
            (string)$data['uri'],
            (string)$data['accountSid'],
            (int)$data['numMedia'],
            (string)$data['status'],
            $data['messagingServiceSid'] ? (string)$data['messagingServiceSid'] : null,
            (string)$data['sid'],
            ($data['dateSent'] instanceof \DateTime)
                ? \DateTimeImmutable::createFromMutable($data['dateSent'])
                : null,
            ($data['dateCreated'] instanceof \DateTime)
                ? \DateTimeImmutable::createFromMutable($data['dateCreated'])
                : null,
            $data['errorCode'] ? (string)$data['errorCode'] : null,
            (string)$data['priceUnit'],
            (string)$data['apiVersion'],
            (array)$data['subresourceUris'],
        );
    }
}
