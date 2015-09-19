<?php

namespace Kontuak\Interactors\PeriodicalMovement\Update;

use Kontuak\Interactors\Exception\EntityNotFound;
use Kontuak\Interactors\Mappings\PeriodicalMovement;
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

    public function newRequest()
    {
        return new Request();
    }

    public function execute(Request $request)
    {
        try {
            $periodicalMovement = $this->source->get(new Id($request->id));
        } catch (\Kontuak\Exception\Source\EntityNotFound $e) {
            throw new EntityNotFound($e);
        }

        $periodicalMovement->updatePeriod(
            Period::factory(
                PeriodicalMovement::$mapPeriodTypeToDomain[$request->periodType],
                $request->periodAmount
            )
        );
        $periodicalMovement->updateConcept($request->concept);
        $periodicalMovement->updateAmount($request->amount);
        $periodicalMovement->updateStarts(new \DateTime($request->starts));
    }
}