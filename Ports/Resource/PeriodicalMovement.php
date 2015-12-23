<?php

namespace Kontuak\Ports\Resource;

class PeriodicalMovement
{
    /** @var string */
    private $id;

    public function __construct(\Kontuak\PeriodicalMovement $periodicalMovement)
    {
        $this->id = $periodicalMovement->id()->toString();
    }

    /**
     * @return string
     */
    public function id()
    {
        return $this->id;
    }
}
