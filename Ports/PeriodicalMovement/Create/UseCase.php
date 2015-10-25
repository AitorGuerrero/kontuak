<?php

namespace Kontuak\Ports\PeriodicalMovement\Create;

use Kontuak\Ports\Mappings\PeriodicalMovement;
use Kontuak\Period;
use Kontuak\PeriodicalMovement\Id;
use Kontuak\PeriodicalMovement\Source;

class UseCase
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
     */
    public function execute(Request $request)
    {
        $this->source->add(
            new \Kontuak\PeriodicalMovement(
                new Id($request->id),
                $request->amount,
                $request->concept,
                new \DateTime($request->starts),
                Period::factory(
                    PeriodicalMovement::$mapPeriodTypeToDomain[$request->periodType],
                    $request->periodAmount
                )
            )
        );
    }
}