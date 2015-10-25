<?php

namespace Kontuak\Ports\Movement\Coming;

use Kontuak\Movement\History;
use Kontuak\Movement\Source;
use Kontuak\Movement\TotalAmountCalculator;
use Kontuak\Movement\Transformer;

class UseCase
{
    /** @var \DateTime */
    private $timeStamp;
    /** @var Transformer */
    private $movementTransformer;
    /** @var History */
    private $history;

    public function __construct(
        History $history,
        Transformer $movementTransformer,
        \DateTime $timeStamp
    ) {

        $this->timeStamp = $timeStamp;
        $this->movementTransformer = $movementTransformer;
        $this->history = $history;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function execute(Request $request)
    {
        $response = new Response();
        $amounts = $this->history->fromDate($this->timeStamp, $request->limit);
        foreach($amounts as $amount) {
            $amount['movement'] = $this->movementTransformer->toResource($amount['movement']);
        }
        $response->movements = array_reverse($amounts);

        return $response;
    }

    /**
     * @return Request
     */
    public function newRequest()
    {
        return new Request();
    }
}