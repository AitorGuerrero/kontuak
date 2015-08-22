<?php

namespace Kontuak\Implementation\InMemory;

use Kontuak\Expenditure;
use Kontuak\ExpendituresCollection as BaseCollection;

class ExpendituresCollection implements BaseCollection
{
    private $collection = [];
    private $identifierCounter = 1;

    public function add(Expenditure $entry)
    {
        $entry->identify(new EntityId($this->identifierCounter++));
        $this->collection[$entry->id()->serialize()] = $entry;
    }

    /**
     * @param EntityId $id
     * @return Entry
     */
    public function find(EntityId $id)
    {
        return $this->collection[$id->serialize()];
    }
}