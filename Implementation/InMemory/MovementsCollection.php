<?php
/**
 * Created by PhpStorm.
 * User: aitor.guerrero
 * Date: 24/8/15
 * Time: 9:40
 */

namespace Kontuak\Implementation\InMemory;

use Kontuak\EntityIdBase;
use Kontuak\Movement;

class MovementsCollection implements \Kontuak\MovementsCollection
{
    protected $collection = [];
    protected $identifierCounter = 1;
    /** @var \DateTimeInterface */
    protected $timeStamp;

    public function __construct(\DateTimeInterface $timeStamp)
    {
        $this->timeStamp = $timeStamp;
    }

    public function add(Movement $movement)
    {
        $movement->identify(new EntityId($this->identifierCounter++));
        $movement->setCreated($this->timeStamp);
        $this->collection[$movement->id()->serialize()] = $movement;
    }

    /**
     * @param \Kontuak\EntityId $id
     * @return Movement
     */
    public function find(\Kontuak\EntityId $id)
    {
        return $this->collection[$id->serialize()];
    }
}