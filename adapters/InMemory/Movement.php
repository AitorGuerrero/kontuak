<?php

namespace Kontuak\Adapters\InMemory;

use Kontuak\Movement\Id;

class Movement extends \Kontuak\Movement
{
    public function __construct(
        Id $id,
        $amount,
        $concept,
        \DateTime $date,
        \DateTime $created
    ) {
        parent::__construct($id, $amount, $concept, $date, $created);
    }
}