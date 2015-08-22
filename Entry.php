<?php

namespace Kontuak;

class Entry extends Movement
{
    /** @var EntityId */
    private $id;

    public function identify(EntityId $id)
    {
        $this->id = $id;
    }

    /**
     * @return EntityId
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @param mixed $amount
     */
    protected function updateAmount($amount)
    {
        $this->amount = abs($amount);
    }
}