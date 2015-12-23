<?php

namespace Kontuak\Movement;

use Kontuak\Movement;

interface Transformer
{
    public function toResource(Movement $entity);
}