<?php

namespace Kontuak\PeriodicalMovement\Event;

use Kontuak\EventManagement\Event;
use Kontuak\PeriodicalMovement;

abstract class Updated extends Event
{
    /** @var PeriodicalMovement */
    private $periodicalMovement;

    public function __construct(PeriodicalMovement $periodicalMovement)
    {
        $this->periodicalMovement = $periodicalMovement;
        parent::__construct();
    }

    /**
     * @return PeriodicalMovement
     */
    public function periodicalMovement()
    {
        return $this->periodicalMovement;
    }
}
