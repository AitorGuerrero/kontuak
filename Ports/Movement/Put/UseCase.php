<?php

namespace Kontuak\Ports\Movement\Put;

use Kontuak\Movement;
use Kontuak\Movement\Id;
use Kontuak\Movement\Source;
use Kontuak\Movement\Transformer;

class UseCase
{

    /** @var Source */
    private $source;
    /** @var \DateTime */
    private $currentTimeStamp;
    /** @var Transformer */
    private $transformer;

    public function __construct(Source $source, Transformer $transformer, \DateTime $currentTimeStamp)
    {
        $this->source = $source;
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
        $movement = $this->source->collection()->byId(Id::parse($request->id()))->current();
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

    /**
     * @param Request $request
     * @return \Kontuak\Movement
     */
    private function makeNewMovement(Request $request)
    {
        $movement = new Movement(
            Id::parse($request->id()),
            $request->amount(),
            $request->concept(),
            new \DateTime($request->date()),
            $this->currentTimeStamp
        );
        $this->source->add($movement);

        return $movement;
    }

    /**
     * @param Request $request
     * @param $movement
     * @throws \Kontuak\InvalidArgumentException
     * @throws \Kontuak\Movement\Exception\InvalidAmount
     */
    private function updateAMovement(Request $request, $movement)
    {
        /** @var \Kontuak\Movement $movement */
        $movement->updateAmount($request->amount());
        $movement->updateConcept($request->concept());
        $movement->updateDate(new \DateTime($request->date()));
    }
}