<?php

namespace Kontuak\Ports\PeriodicalMovement;

use Kontuak\IsoDateTime;
use Kontuak\Ports\PeriodicalMovement\Create\Request;
use Kontuak\Ports\Mappings\PeriodicalMovement;
use Kontuak\Period;
use Kontuak\PeriodicalMovement\Id;
use Kontuak\PeriodicalMovement\Source;

class Create
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
                Id::parse($request->id),
                $request->amount,
                $request->concept,
                new \DateTime($request->starts),
                Period\Factory::fromType(
                    PeriodicalMovement::$mapPeriodTypeToDomain[$request->periodType],
                    $request->periodAmount,
                    new IsoDateTime($request->starts)
                )
            )
        );
    }
}