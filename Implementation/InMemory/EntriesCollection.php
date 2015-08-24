<?php

namespace Kontuak\Implementation\InMemory;

use Kontuak\Entry;
use Kontuak\EntriesCollection as BaseCollection;

class EntriesCollection implements BaseCollection
{
    /**
     * @var MovementsCollection
     */
    private $collection;

    public function __construct(MovementsCollection $collection)
    {
        $this->collection = $collection;
    }

    public function add(Entry $entry)
    {
        return $this->collection->add($entry);
    }

    /**
     * @param EntityId $id
     * @return Entry
     */
    public function find(EntityId $id)
    {
        return $this->collection->find($id);
    }
}