<?php

namespace Kontuak\Ports\Movement;

use Kontuak\Movement\ExistsChecker;
use Kontuak\Movement\Id;

class Exists
{
    /** @var ExistsChecker */
    private $existsChecker;

    public function __construct(ExistsChecker $existsChecker)
    {
        $this->existsChecker = $existsChecker;
    }

    /**
     * @param string $movementId
     * @return bool
     */
    public function execute($movementId)
    {
        return $this->existsChecker->isExistingMovementWithId(Id::parse($movementId));
    }
}
