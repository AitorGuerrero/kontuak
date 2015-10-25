<?php

namespace Kontuak\Ports\PeriodicalMovement\GetAll;

use Kontuak\PeriodicalMovement\Source;
use Kontuak\PeriodicalMovement\Transformer;

class UseCase
{

    /** @var Source */
    private $source;
    /** @var Transformer */
    private $transformer;

    public function __construct(Source $source, Transformer $transformer)
    {
        $this->source = $source;
        $this->transformer = $transformer;
    }

    /**
     * @return Request
     */
    public function newRequest()
    {
        return new Request();
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function execute(Request $request)
    {
        $response = new Response();
        $i = 0;
        foreach($this->source->collection() as $periodicalMovement) {
            $response->periodicalMovements[] = $this->transformer->toResource($periodicalMovement);
            $i++;
            if(!is_null($request->limit) && $i >= $request->limit) {
                break;
            }
        }

        return $response;
    }
}