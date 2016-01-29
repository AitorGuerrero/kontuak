<?php

namespace Kontuak\Ports\PeriodicalMovement\Put;

class Request 
{
    private $id;
    private $concept;
    private $amount;
    private $startDate;
    private $periodType;
    private $periodAmount;
    private $endDate;

    public function __construct($id, $concept, $amount, $startDate, $endDate, $periodType, $periodAmount)
    {
        $this->id = $id;
        $this->concept = $concept;
        $this->amount = $amount;
        $this->startDate = $startDate;
        $this->periodType = $periodType;
        $this->periodAmount = $periodAmount;
        $this->endDate = $endDate;
    }

    /**
     * @return mixed
     */
    public function startDate()
    {
        return $this->startDate;
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

    /**
     * @return mixed
     */
    public function endDate()
    {
        return $this->endDate;
    }
}