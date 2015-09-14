<?php

namespace Kontuak\PeriodicalMovement\Id;

use Kontuak\PeriodicalMovement\Id;
use Kontuak\UUIDv4\Generator as BaseGenerator;

class Generator extends BaseGenerator
{
    /**
     * @return Id
     */
    public function generate()
    {
        return new Id($this->gen_uuid());
    }
}