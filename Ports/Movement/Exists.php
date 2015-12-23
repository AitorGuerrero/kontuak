<?php

namespace Kontuak\Ports\Movement;

use Kontuak\Movement\ExistsChecker;

class Exists
{
    /** @var ExistsChecker */
    private $existsChecker;

    public function __construct(ExistsChecker $existsChecker)
    {
        $this->existsChecker = $existsChecker;
    }

    public function execute($movementId)
    {
        return $this->existsChecker->isExistingMovementWithId($movementId);
    }
}
