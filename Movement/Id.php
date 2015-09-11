<?php

namespace Kontuak\Movement;

class Id
{
    /** @var string */
    private $serialized;

    /**
     * @param $serialized
     * TODO Check well formed UUIDv4
     */
    public function __construct($serialized)
    {
        $this->serialized = $serialized;
    }

    public function serialize()
    {
        return $this->serialized;
    }
}