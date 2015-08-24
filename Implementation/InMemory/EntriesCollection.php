<?php

namespace Kontuak\Implementation\InMemory;

use Kontuak\Entry;
use Kontuak\EntriesCollection as BaseCollection;

class EntriesCollection implements BaseCollection
{
    private $collection = [];
    private $identifierCounter = 1;
    /** @var \DateTimeInterface */
    private $timeStamp;

    public function __construct(\DateTimeInterface $timeStamp)
    {
        $this->timeStamp = $timeStamp;
    }

    public function add(Entry $entry)
    {
        $entry->identify(new EntityId($this->identifierCounter++));
        $entry->setCreated($this->timeStamp);
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