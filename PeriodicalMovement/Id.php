<?php

namespace Kontuak\PeriodicalMovement;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Id
{
    /** @var UuidInterface */
    private $uuid;

    private function __construct(UuidInterface $uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * @return Id
     */
    public static function make()
    {
        return new self(Uuid::uuid4());
    }

    /**
     * @param string $stringId
     * @return Id
     */
    public static function parse($stringId)
    {
        return new self(UUid::fromString($stringId));
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->uuid->toString();
    }
}