<?php

namespace Kontuak\Movement;

interface Factory 
{
    /**
     * @param Id $movementId
     * @param $amount
     * @param $concept
     * @param \DateTime $date
     * @param \DateTime $created
     * @return \Kontuak\Movement
     */
    public function make(Id $movementId, $amount, $concept, \DateTime $date, \DateTime $created);
}