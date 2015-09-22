<?php

namespace Kontuak\Interactors\PeriodicalMovement\Put;

class Response 
{
    private $isNew;
    private $movement;

    public function __construct($isNew, $movement)
    {
        $this->isNew = $isNew;
        $this->movement = $movement;
    }

    /**
     * @return mixed
     */
    public function movement()
    {
        return $this->movement;
    }

    /**
     * @return mixed
     */
    public function isNew()
    {
        return $this->isNew;
    }
}