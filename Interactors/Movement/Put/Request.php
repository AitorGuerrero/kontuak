<?php

namespace Kontuak\Interactors\Movement\Put;

class Request 
{
    private $id;
    private $concept;
    private $amount;
    private $date;

    public function __construct($id, $concept, $amount, $date)
    {

        $this->id = $id;
        $this->concept = $concept;
        $this->amount = $amount;
        $this->date = $date;
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
}