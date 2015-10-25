<?php

namespace Kontuak\Adapters\Transformer;

use Kontuak\Movement\Transformer;

class Movement implements Transformer
{

    public function toResource(\Kontuak\Movement $entity)
    {
        return $entity;
    }
}