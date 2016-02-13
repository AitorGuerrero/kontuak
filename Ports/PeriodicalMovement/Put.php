<?php

namespace Kontuak\Ports\PeriodicalMovement;

use Kontuak\CurrentDateTimeProvider;
use Kontuak\IsoDateTime;
use Kontuak\Ports\Mappings;
use Kontuak\Period;
use Kontuak\PeriodicalMovement;
use Kontuak\Ports\PeriodicalMovement\Put\Request;

class Put
{
    /** @var PeriodicalMovement\Source */
    private $periodicalMovementSource;
    /** @var CurrentDateTimeProvider */
    private $currentDateTimeProvider;

    public function __construct(PeriodicalMovement\Source $source, CurrentDateTimeProvider $currentDateTimeProvider)
    {
        $this->periodicalMovementSource = $source;
        $this->currentDateTimeProvider = $currentDateTimeProvider;
    }

    /**
     * @param Request $request
     */
    public function execute(Request $request)
    {
        $periodicalMovement = $this->periodicalMovementSource
            ->collection()
            ->byId(PeriodicalMovement\Id::parse($request->id()))
            ->current();
        if (!$periodicalMovement) {
            $this->makeNewPeriodicalMovement($request);
        } else {
            $this->updateAPeriodicalMovement($request, $periodicalMovement);
        }
    }

    private function makeNewPeriodicalMovement(Request $request)
    {
        return new PeriodicalMovement(
            PeriodicalMovement\Id::parse($request->id()),
            $request->amount(),
            $request->concept(),
            Period\Factory::fromType(
                Mappings\PeriodicalMovement::$mapPeriodTypeToDomain[$request->periodType()],
                $request->periodAmount(),
                new IsoDateTime($request->startDate()),
                new IsoDateTime($request->endDate())
            ),
            $this->currentDateTimeProvider->getCurrentDateTime()
        );
    }

    /**
     * @param Request $request
     * @param $periodicalMovement
     * @throws \Kontuak\InvalidArgumentException
     * @throws \Kontuak\Movement\Exception\InvalidAmount
     */
    private function updateAPeriodicalMovement(Request $request, PeriodicalMovement $periodicalMovement)
    {
        /** @var PeriodicalMovement $periodicalMovement */
        $periodicalMovement->updateAmount($request->amount());
        $periodicalMovement->updateConcept($request->concept());
        $periodicalMovement->updatePeriod(Period\Factory::fromType(
            Mappings\PeriodicalMovement::$mapPeriodTypeToDomain[$request->periodType()],
            $request->periodAmount(),
            new IsoDateTime($request->startDate()),
            new IsoDateTime($request->endDate())
        ));
    }
}
