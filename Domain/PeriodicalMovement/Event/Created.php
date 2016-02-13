<?php

namespace Kontuak\PeriodicalMovement\Event;

use Kontuak\EventManagement\Event;
use Kontuak\PeriodicalMovement;

class Created extends Event
{
    /** @var PeriodicalMovement */
    private $periodicalMovement;

    public function __construct(PeriodicalMovement $periodicalMovement)
    {
        $this->periodicalMovement = $periodicalMovement;
    }

    /**
     * @return PeriodicalMovement
     */
    public function periodicalMovement()
    {
        return $this->periodicalMovement;
    }
}
