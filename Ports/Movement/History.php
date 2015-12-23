<?php

namespace Kontuak\Ports\Movement;

use Kontuak\Movement;
use Kontuak\Ports\Movement\History\Request;
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

    public function execute(Request $request)
    {
        $collection = $this->source->collection();
        if(!is_null($request->fromDate)) {
            $collection->filterByDateIsPostThan(new \DateTime($request->fromDate));
        }
        if(!is_null($request->toDate)) {
            $collection->filterDateLessOrEqualTo(new \DateTime($request->toDate));
        }
        $amounts = $this->calculator->getForACollection($collection, $request->limit);
        foreach($amounts as $i => $amount) {
            $amounts[$i]['movement'] = new Resource\Movement($amount['movement']);
        }
        $amounts = array_reverse($amounts);

        return $amounts;
    }
}
