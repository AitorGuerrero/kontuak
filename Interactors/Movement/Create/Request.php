<?php

namespace Kontuak\Interactors\Movement\Create;

class Request
{
    /** @var string */
    public $id;
    /** @var float  */
    public $amount;
    /** @var string */
    public $concept;
    /** @var string */
    public $date;
}