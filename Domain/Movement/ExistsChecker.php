<?php

namespace Kontuak\Movement;

use Kontuak\Exception\Source\EntityNotFound;

class ExistsChecker
{
    /** @var Source */
    private $source;

    public function __construct(Source $source)
    {
        $this->source = $source;
    }

    public function isExistingMovementWithId($id)
    {
        try {
            $this->source->get($id);
        } catch (EntityNotFound $exception) {
            return false;
        }
        return true;
    }
}
