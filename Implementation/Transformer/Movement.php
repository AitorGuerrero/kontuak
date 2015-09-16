<?php

namespace Kontuak\Implementation\Transformer;

use Kontuak\Movement\Transformer;

class Movement implements Transformer
{

    public function toResource(\Kontuak\Movement $entity)
    {
        return $entity;
    }
}