<?php

namespace Kontuak\Implementation\InMemory;

use Kontuak\Movement\Id;

class Movement extends \Kontuak\Movement
{
    public function __construct(
        Id $movementId,
        $amount,
        $concept,
        \DateTime $date,
        \DateTime $created
    ) {
        parent::__construct($movementId, $amount, $concept, $date, $created);
    }
}