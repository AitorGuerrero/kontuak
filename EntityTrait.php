<?php

namespace Kontuak;

trait EntityTrait
{
    /**
     * @var EntityId
     */
    private $id;

    /**
     * @param EntityId $id
     */
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
}