<?php

namespace Kontuak\Interactors\PeriodicalMovement\Create;

class Request 
{
    const PERIOD_TYPE_DAYS = 'days';
    const PERIOD_TYPE_MONTHS = 'months';

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