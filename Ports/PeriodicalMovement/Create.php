<?php

namespace Kontuak\Ports\PeriodicalMovement;

use Kontuak\CurrentDateTimeProvider;
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
    /** @var CurrentDateTimeProvider */
    private $currentDateTimeProvider;

    public function __construct(Source $source, CurrentDateTimeProvider $currentDateTimeProvider)
    {
        $this->source = $source;
        $this->currentDateTimeProvider = $currentDateTimeProvider;
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
                Period\Factory::fromType(
                    PeriodicalMovement::$mapPeriodTypeToDomain[$request->periodType],
                    $request->periodAmount,
                    new IsoDateTime($request->starts)
                ),
                $this->currentDateTimeProvider->getCurrentDateTime()
            )
        );
    }
}