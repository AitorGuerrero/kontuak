<?php

namespace Kontuak\Adapters\InMemory;

trait Collection
{
    /** @var array */
    protected $collection = [];

    public function current()
    {
        return current($this->collection);
    }

    public function next()
    {
        return next($this->collection);
    }

    public function key()
    {
        return key($this->collection);
    }

    public function valid()
    {
        return isset($this->collection[key($this->collection)]);
    }

    public function rewind()
    {
        reset($this->collection);
    }

    public function count()
    {
        return count($this->collection);
    }
}