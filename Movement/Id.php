<?php

namespace Kontuak\Movement;

class Id
{
    /** @var string */
    private $serialized;

    public function __construct($serialized)
    {
        $this->serialized = $serialized;
    }

    public function serialize()
    {
        return $this->serialized;
    }

    /**
     * @param $string
     * @return Id
     * TODO KILL IT!!!
     */
    public static function fromString($string)
    {
        $id = new self($string);
        return $id;
    }
}