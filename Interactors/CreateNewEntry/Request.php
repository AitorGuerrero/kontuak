<?php

namespace Kontuak\Interactors\CreateNewEntry;

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