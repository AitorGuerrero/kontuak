<?php

namespace Kontuak\Interactors\Movement\GetOne;

use Kontuak\Interactors\Exception\EntityNotFoundException;
use Kontuak\Movement;

class UseCase
{

    /** @var Movement\Source */
    private $source;
    /** @var \Kontuak\Implementation\Transformer\Movement */
    private $movementTransformer;

    public function __construct(
        Movement\Source $source,
        Movement\Transformer $movementTransformer
    ) {

        $this->source = $source;
        $this->movementTransformer = $movementTransformer;
    }

    public function execute(Request $response)
    {
        try {
            $movement = $this
                ->source
                ->collection()
                ->findById(new Movement\Id($response->id));
        } catch (Movement\Collection\MovementNotFoundException $e) {
            throw new EntityNotFoundException();
        }

        $response = new Response();
        $response->movement = $this->movementTransformer->toResource($movement);

        return $response;
    }
}