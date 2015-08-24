<?php

namespace Kontuak\Implementation\InMemory;

use Kontuak\Expenditure;
use Kontuak\ExpendituresCollection as BaseCollection;

class ExpendituresCollection implements BaseCollection
{
    private $collection = [];
    private $identifierCounter = 1;
    /** @var \DateTimeInterface */
    private $timeStamp;

    public function __construct(\DateTimeInterface $timeStamp)
    {
        $this->timeStamp = $timeStamp;
    }

    public function add(Expenditure $entry)
    {
        $entry->identify(new EntityId($this->identifierCounter++));
        $entry->setCreated($this->timeStamp);
        $this->collection[$entry->id()->serialize()] = $entry;
    }

    /**
     * @param EntityId $id
     * @return Expenditure
     */
    public function find(EntityId $id)
    {
        return $this->collection[$id->serialize()];
    }
}