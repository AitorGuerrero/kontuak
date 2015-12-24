<?php

namespace Kontuak\Ports\PeriodicalMovement;

use Kontuak\IsoDateTime;
use Kontuak\Ports\Exception\EntityNotFound;
use Kontuak\Ports\Mappings\PeriodicalMovement;
use Kontuak\Period;
use Kontuak\PeriodicalMovement\Id;
use Kontuak\PeriodicalMovement\Source;
use Kontuak\Ports\PeriodicalMovement\Update\Request;

class Update
{
    /** @var Source */
    private $source;

    public function __construct(Source $source)
    {
        $this->source = $source;
    }

    public function newRequest()
    {
        return new Request();
    }

    public function execute(Request $request)
    {
        try {
            $periodicalMovement = $this->source->get(Id::parse($request->id));
        } catch (\Kontuak\Exception\Source\EntityNotFound $e) {
            throw new EntityNotFound($e);
        }

        $periodicalMovement->updatePeriod(
            Period\Factory::fromType(
                PeriodicalMovement::$mapPeriodTypeToDomain[$request->periodType],
                $request->periodAmount,
                new IsoDateTime($request->starts)
            )
        );
        $periodicalMovement->updateConcept($request->concept);
        $periodicalMovement->updateAmount($request->amount);
        $periodicalMovement->updateStarts(new IsoDateTime($request->starts));
    }
}