<?php

namespace Kontuak\Implementation\InMemory;

use Kontuak\Expenditure;
use Kontuak\ExpendituresCollection as BaseCollection;

class ExpendituresCollection implements BaseCollection
{
    /**
     * @var MovementsCollection
     */
    private $movementsCollection;

    public function __construct(MovementsCollection $movementsCollection)
    {
        $this->movementsCollection = $movementsCollection;
    }

    public function add(Expenditure $movement)
    {
        return $this->movementsCollection->add($movement);
    }

    public function find(EntityId $id)
    {
        return $this->movementsCollection->find($id);
    }
}