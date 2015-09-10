<?php

namespace Kontuak\Movement;

class Id
{
    /** @var string */
    private $uniqueId;

    public function __construct($id = null)
    {
        if($id === null) {
            $id = uniqid();
        }
        $this->uniqueId = $id;
    }

    public function serialize()
    {
        return $this->uniqueId;
    }

    public static function fromString($string)
    {
        $id = new self();
        $id->uniqueId = $string;
        return $id;
    }
}