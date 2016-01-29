<?php

namespace Kontuak\Ports\Movement;

use Kontuak\Movement;
use Kontuak\Ports\Resource;

class History
{
    /** @var Movement\TotalAmountCalculator */
    private $calculator;
    /** @var Movement\Source */
    private $source;

    public function __construct(
        Movement\Source $source,
        Movement\TotalAmountCalculator $calculator
    ) {
        $this->calculator = $calculator;
        $this->source = $source;
    }

    /**
     * @param string|null $fromIsoDate
     * @param string|null $toIsoDate
     * @param int|null $limit
     * @return Resource\Movement\Transaction[]
     */
    public function execute($fromIsoDate = null, $toIsoDate = null, $limit = null)
    {
        $collection = $this->source->collection();
        if(!is_null($fromIsoDate)) {
            $collection = $collection->filterByDateIsPostThan(new \DateTime($fromIsoDate));
        }
        if(!is_null($toIsoDate)) {
            $collection = $collection->filterDateLessOrEqualTo(new \DateTime($toIsoDate));
        }
        $transactions = $this->calculator->getForACollection($collection, $limit);
        $output = [];
        foreach($transactions as $i => $transaction) {
            $output[] = new Resource\Movement\Transaction($transaction);
        }

        return $output;
    }
}
