<?php

namespace Kontuak\EventManagement;

use DateTime;

abstract class Event
{
    /**
     * @var DateTime
     */
    private $occurredOn;

    public function __construct()
    {
        $this->occurredOn = new DateTime();
    }

    /**
     * @return DateTime
     */
    public function occurredOn()
    {
        return $this->occurredOn;
    }
}
