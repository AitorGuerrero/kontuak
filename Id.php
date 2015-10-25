<?php

namespace Kontuak;

abstract class Id
{
    /**
     * @var string
     */
    private $serialized;

    /**
     * @param string $serialized
     */
    public function __construct($serialized)
    {
        $this->serialized = $serialized;
    }

    /**
     * @return string
     */
    public function serialize()
    {
        return $this->serialized;
    }
}