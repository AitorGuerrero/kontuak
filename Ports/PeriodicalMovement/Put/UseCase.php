<?php

namespace Kontuak\Ports\PeriodicalMovement\Put;

use Kontuak\Ports\Mappings\PeriodicalMovement;
use Kontuak\Period;
use Kontuak\PeriodicalMovement\Id;
use Kontuak\PeriodicalMovement\Source;
use Kontuak\PeriodicalMovement\Transformer;

class UseCase
{
    /** @var Source */
    private $source;
    /** @var \DateTime */
    private $currentTimeStamp;

    public function __construct(Source $source, \DateTime $currentTimeStamp)
    {
        $this->source = $source;
        $this->currentTimeStamp = $currentTimeStamp;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function execute(Request $request)
    {
        $movement = $this->source->collection()->byId(Id::parse($request->id()))->current();
        if(!$movement) {
            $this->makeNewMovement($request);
        } else {
            $this->updateAMovement($request, $movement);

        }
    }

    private function makeNewMovement(Request $request)
    {
        return $movement = new \Kontuak\PeriodicalMovement(
            Id::parse($request->id()),
            $request->amount(),
            $request->concept(),
            new \DateTime($request->date()),
            Period::factory(
                PeriodicalMovement::$mapPeriodTypeToDomain[$request->periodType()],
                $request->periodAmount()
            ),
            $this->currentTimeStamp
        );
    }

    /**
     * @param Request $request
     * @param $movement
     * @throws \Kontuak\InvalidArgumentException
     * @throws \Kontuak\Movement\Exception\InvalidAmount
     */
    private function updateAMovement(Request $request, $movement)
    {
        /** @var \Kontuak\PeriodicalMovement $movement */
        $movement->updateAmount($request->amount());
        $movement->updateConcept($request->concept());
        $movement->updateStarts(new \DateTime($request->date()));
    }
}