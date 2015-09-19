<?php

namespace Kontuak\Interactors\PeriodicalMovement\Create;

class Request 
{
    /** @var string */
    public $id;
    /** @var string */
    public $concept;
    /** @var int */
    public $amount;
    /** @var string */
    public $starts;
    /** @var string */
    public $periodType;
    /** @var int */
    public $periodAmount;
}