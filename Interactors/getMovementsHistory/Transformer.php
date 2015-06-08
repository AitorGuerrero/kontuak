<?php

namespace Kontuak\Interactors\GetMovementsHistory;

interface Transformer {
    /**
     * @param []Movements $movements
     * @return mixed
     */
    public function transform($movements);
}