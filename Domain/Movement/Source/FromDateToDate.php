<?php

namespace Kontuak\Movement\Source;

use Kontuak\IsoDateTime;
use Kontuak\Movement\Source;

class FromDateToDate
{
    /** @var Source */
    private $source;

    public function __construct(Source $source)
    {
        $this->source = $source;
    }

    public function get(IsoDateTime $fromDate, IsoDateTime $toDate)
    {
        return $this
            ->source
            ->collection()
            ->filterByDateIsPostThan($fromDate)
            ->filterDateLessThan($toDate);
    }
}
