<?php

namespace Kontuak\PeriodicalMovement;

use Kontuak\PeriodicalMovement;

interface Collection extends \Iterator, \Countable
{
    /**
     * @param Id $id
     * @return Collection
     */
    public function byId(Id $id);
}