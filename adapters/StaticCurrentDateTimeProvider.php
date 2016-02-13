<?php

namespace Kontuak\Adapters;

use DateTimeImmutable;
use Kontuak\CurrentDateTimeProvider;

class StaticCurrentDateTimeProvider implements CurrentDateTimeProvider
{
    /** @var DateTimeImmutable */
    private $currentDateTime;

    public function __construct(DateTimeImmutable $currentDateTime)
    {
        $this->currentDateTime = $currentDateTime;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCurrentDateTime()
    {
        return $this->currentDateTime;
    }
}

