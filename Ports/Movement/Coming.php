<?php

namespace Kontuak\Ports\Movement;

use Kontuak\Movement\History;
use Kontuak\Movement\Transformer;
use Kontuak\Ports\Movement\Coming\Request;
use Kontuak\Ports\Resource\Movement;

class Coming
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
     * @return Movement[]
     */
    public function execute(Request $request)
    {
        $amounts = $this->history->fromDate($this->timeStamp, $request->limit);
        foreach($amounts as $amount) {
            $amount['movement'] = $this->movementTransformer->toResource($amount['movement']);
        }
        $movements = array_reverse($amounts);

        return $movements;
    }

    /**
     * @return Request
     */
    public function newRequest()
    {
        return new Request();
    }
}