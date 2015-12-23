<?php

namespace Kontuak\Ports\Resource;

class Movement
{
    /** @var string */
    private $id;
    /** @var float */
    private $amount;
    /** @var string */
    private $concept;
    /** @var \DateTime */
    private $date;

    public function __construct(\Kontuak\Movement $movement)
    {
        $this->id = $movement->id()->toString();
        $this->amount = $movement->amount();
        $this->concept = $movement->concept();
        $this->date = $movement->date();
    }

    /**
     * @return string
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return float
     */
    public function amount()
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function concept()
    {
        return $this->concept;
    }

    /**
     * @return \DateTime
     */
    public function date()
    {
        return $this->date;
    }
}
