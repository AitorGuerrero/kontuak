<?php

namespace Kontuak\Interactors\Movement\History;

use Kontuak\Interactors\InvalidArgumentException;
use Kontuak\Movement;

class UseCase
{
    /** @var \DateTime */
    private $today;
    /** @var Movement\History */
    private $history;
    /** @var \Kontuak\Implementation\Transformer\Movement */
    private $movementTransformer;

    public function __construct(
        Movement\History $history,
        Movement\Transformer $movementTransformer,
        \DateTime $today
    ) {
        $this->today = $today;
        $this->history = $history;
        $this->movementTransformer = $movementTransformer;
    }

    public function execute(Request $request)
    {
        $this->assertRequest($request);
        $amounts = $this->history->toDate($this->today);
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