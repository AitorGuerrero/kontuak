<?php

namespace Kontuak\Interactors\PeriodicalMovement\GetOne;

use Kontuak\Exception\Source\EntityNotFound;
use Kontuak\PeriodicalMovement\Id;
use Kontuak\PeriodicalMovement\Source;
use Kontuak\PeriodicalMovement\Transformer;

class UseCase
{

    /** @var Source */
    private $source;
    /** @var Transformer */
    private $transformer;

    public function __construct(Source $source, Transformer $transformer)
    {

        $this->source = $source;
        $this->transformer = $transformer;
    }

    public function newRequest()
    {
        return new Request();
    }

    /**
     * @param Request $request
     * @throws \Kontuak\Interactors\Exception\EntityNotFound
     * @return Response
     */
    public function execute(Request $request)
    {
        $response = new Response();
        try {
            $response->periodicalMovement = $this->transformer->toResource(
                $this->source->get(new Id($request->id))
            );
        } catch (EntityNotFound $e) {
            throw new \Kontuak\Interactors\Exception\EntityNotFound($e);
        }

        return $response;
    }
}