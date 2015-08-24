<?php

namespace Kontuak\Interactors\CreateNewExpenditure;

class Request
{
    /** @var float */
    public $amount;
    /** @var string */
    public $concept;
    /** @var string */
    public $dateTimeSerialized;
}