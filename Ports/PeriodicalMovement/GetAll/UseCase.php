<?php

namespace Kontuak\Ports\PeriodicalMovement\GetAll;

use Kontuak\PeriodicalMovement\Source;
use Kontuak\PeriodicalMovement\Transformer;
use Kontuak\Ports\Resource\PeriodicalMovement;

class UseCase
{

    /** @var Source */
    private $source;
    /** @var Transformer */
    private $transformer;

    public function __construct(Source $source)
    {
        $this->source = $source;
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
     * @return PeriodicalMovement[]
     */
    public function execute(Request $request)
    {
        $response = [];
        $i = 0;
        foreach($this->source->collection() as $periodicalMovement) {
            $response[] = new PeriodicalMovement($periodicalMovement);
            $i++;
            if(!is_null($request->limit) && $i >= $request->limit) {
                break;
            }
        }

        return $response;
    }
}