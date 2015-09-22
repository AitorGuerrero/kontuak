<?php

namespace Kontuak\Interactors\PeriodicalMovement\Put;

use Kontuak\Interactors\Mappings\PeriodicalMovement;
use Kontuak\Period;
use Kontuak\PeriodicalMovement\Factory;
use Kontuak\PeriodicalMovement\Id;
use Kontuak\PeriodicalMovement\Source;
use Kontuak\PeriodicalMovement\Transformer;

class UseCase
{
    /** @var Source */
    private $source;
    /** @var Factory */
    private $factory;
    /** @var \DateTime */
    private $currentTimeStamp;
    /** @var Transformer */
    private $transformer;

    public function __construct(Source $source, Transformer $transformer, Factory $factory, \DateTime $currentTimeStamp)
    {
        $this->source = $source;
        $this->factory = $factory;
        $this->currentTimeStamp = $currentTimeStamp;
        $this->transformer = $transformer;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function execute(Request $request)
    {
        $created = false;
        $movement = $this->source->collection()->byId(new Id($request->id()))->current();
        if(!$movement) {
            $created = true;
            $movement = $this->makeNewMovement($request);
        } else {
            $this->updateAMovement($request, $movement);

        }

        return new Response(
            $created,
            $this->transformer->toResource($movement)
        );
    }

    private function makeNewMovement(Request $request)
    {
        return $movement = $this->factory->make(
            new Id($request->id()),
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