<?php

namespace Kontuak\Interactors\PeriodicalMovement\Put;

class Request 
{
    private $id;
    private $concept;
    private $amount;
    private $date;
    private $periodType;
    private $periodAmount;

    public function __construct($id, $concept, $amount, $date, $periodType, $periodAmount)
    {
        $this->id = $id;
        $this->concept = $concept;
        $this->amount = $amount;
        $this->date = $date;
        $this->periodType = $periodType;
        $this->periodAmount = $periodAmount;
    }

    /**
     * @return mixed
     */
    public function date()
    {
        return $this->date;
    }

    /**
     * @return mixed
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function concept()
    {
        return $this->concept;
    }

    /**
     * @return mixed
     */
    public function amount()
    {
        return $this->amount;
    }

    /**
     * @return mixed
     */
    public function periodAmount()
    {
        return $this->periodAmount;
    }

    /**
     * @return mixed
     */
    public function periodType()
    {
        return $this->periodType;
    }
}