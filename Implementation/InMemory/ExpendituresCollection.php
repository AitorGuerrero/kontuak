<?php

namespace kontuak\Implementation\InMemory;

use kontuak\Expenditure;
use kontuak\ExpendituresCollection as BaseCollection;

class ExpendituresCollection implements BaseCollection
{
    private $collection = [];

    public function add(Expenditure $expenditure)
    {
        $this->collection[] = $expenditure;
    }
}