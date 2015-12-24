<?php

namespace Kontuak\PeriodicalMovement\Event;

use Kontuak\PeriodicalMovement;

abstract class AttributeUpdated extends Updated
{
    /** @var string */
    private $oldValue;
    /** @var string */
    private $newValue;

    public function __construct(PeriodicalMovement $periodicalMovement, $oldValue, $newValue)
    {
        $this->oldValue = $oldValue;
        $this->newValue = $newValue;
        parent::__construct($periodicalMovement);
    }

    /**
     * @return string
     */
    abstract protected function attributeName();

    /**
     * @return string
     */
    public function oldValue()
    {
        return $this->oldValue;
    }

    /**
     * @return string
     */
    public function newValue()
    {
        return $this->newValue;
    }
}
