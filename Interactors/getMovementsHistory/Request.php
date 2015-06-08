<?php

namespace Kontuak\Interactors\GetMovementsHistory;

class Request
{
    public $limit;
    public $page;
    public $toDate;

    public function __construct($limit, $page, $toDate)
    {
        $this->limit = $limit;
        $this->page = $page;
        $this->toDate = $toDate;
    }
}