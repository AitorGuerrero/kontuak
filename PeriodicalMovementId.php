<?php

namespace Kontuak;

class PeriodicalMovementId
{
    /** @var string */
    private $uniqueId;

    public function __construct()
    {
        $this->uniqueId = uniqid();
    }

    public static function fromString($string)
    {
        $id = new self();
        $id->uniqueId = $string;
        return $id;
    }

    public function serialize()
    {
        return $this->uniqueId;
    }
}