<?php

namespace Kontuak\Interactors\Movement\Create;

class Request
{
    const PERIOD_TYPE_DAYS = 'days';
    const PERIOD_TYPE_MONTHS = 'months';

    /** @var string */
    public $id;
    /** @var float  */
    public $amount;
    /** @var string */
    public $concept;
    /** @var string */
    public $date;
    /** @var bool */
    public $isPeriodical;
    /** @var string */
    public $periodType;
    /** @var int */
    public $periodAmount;
}