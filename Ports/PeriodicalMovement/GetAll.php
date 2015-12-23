<?php

namespace Kontuak\Ports\PeriodicalMovement;

use Kontuak\PeriodicalMovement\Source;
use Kontuak\Ports\PeriodicalMovement\GetAll\Request;
use Kontuak\Ports\Resource\PeriodicalMovement;

class GetAll
{

    /** @var Source */
    private $source;

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