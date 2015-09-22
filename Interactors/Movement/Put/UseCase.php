<?php

namespace Kontuak\Interactors\Movement\Put;

use Kontuak\Movement\Factory;
use Kontuak\Movement\Id;
use Kontuak\Movement\Source;
use Kontuak\Movement\Transformer;

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

    /**
     * @param Request $request
     * @return \Kontuak\Movement
     */
    private function makeNewMovement(Request $request)
    {
        return $movement = $this->factory->make(
            new Id($request->id()),
            $request->amount(),
            $request->concept(),
            new \DateTime($request->date()),
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
        /** @var \Kontuak\Movement $movement */
        $movement->updateAmount($request->amount());
        $movement->updateConcept($request->concept());
        $movement->updateDate(new \DateTime($request->date()));
    }
}