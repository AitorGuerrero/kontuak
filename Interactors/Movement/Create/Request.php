<?php

namespace Kontuak\Interactors\Movement\Create;

class Request
{
    /** @var float  */
    public $amount;
    /** @var string */
    public $concept;
    /** @var string */
    public $date;
}