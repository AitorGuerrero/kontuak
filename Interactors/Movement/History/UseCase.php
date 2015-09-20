<?php

namespace Kontuak\Interactors\Movement\History;

use Kontuak\Interactors\InvalidArgumentException;
use Kontuak\Movement;

class UseCase
{
    /** @var \DateTime */
    private $today;
    /** @var \Kontuak\Implementation\Transformer\Movement */
    private $movementTransformer;
    /** @var Movement\TotalAmountCalculator */
    private $calculator;
    /** @var Movement\Source */
    private $source;

    public function __construct(
        Movement\Source $source,
        Movement\TotalAmountCalculator $calculator,
        Movement\Transformer $movementTransformer
    ) {
        $this->movementTransformer = $movementTransformer;
        $this->calculator = $calculator;
        $this->source = $source;
    }

    public function execute(Request $request)
    {
        $this->assertRequest($request);
        $collection = $this->source->collection();
        if(!is_null($request->fromDate)) {
            $collection->filterByDateIsPostThan(new \DateTime($request->fromDate));
        }
        if(!is_null($request->toDate)) {
            $collection->filterDateLessOrEqualTo(new \DateTime($request->toDate));
        }
        $amounts = $this->calculator->getForACollection($collection, $request->limit);
        foreach($amounts as $amount) {
            $amount['movement'] = $this->movementTransformer->toResource($amount['movement']);
        }
        $response = new Response();
        $response->amounts = array_reverse($amounts);

        return $response;
    }

    /**
     * @param Request $request
     * @throws InvalidArgumentException
     */
    private function assertRequest(Request $request)
    {
        if (gettype($request->limit) !== 'integer') {
            throw new InvalidArgumentException('Required argument "limit"');
        }
    }
}