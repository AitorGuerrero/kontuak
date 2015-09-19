<?php

namespace kontuak\Implementation\InMemory\PeriodicalMovement;

use Kontuak\PeriodicalMovement;

class Collection implements PeriodicalMovement\Collection
{
    use \Kontuak\Implementation\InMemory\Collection;

    /** @var PeriodicalMovement\Source */
    private $source;

    public function __construct(Source $source)
    {
        $this->source = $source;
        $this->collection = $this->source->toArray();
    }
}