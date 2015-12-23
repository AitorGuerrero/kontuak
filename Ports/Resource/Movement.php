<?php

namespace Kontuak\Ports\Resource;

class Movement
{
    /** @var string */
    private $id;

    public function __construct(\Kontuak\Movement $movement)
    {
        $this->id = $movement->id()->toString();
    }

    /**
     * @return string
     */
    public function id()
    {
        return $this->id;
    }
}
