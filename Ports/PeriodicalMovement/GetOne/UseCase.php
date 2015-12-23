<?php

namespace Kontuak\Ports\PeriodicalMovement\GetOne;

use Kontuak\Exception\Source\EntityNotFound;
use Kontuak\PeriodicalMovement\Id;
use Kontuak\PeriodicalMovement\Source;
use Kontuak\PeriodicalMovement\Transformer;
use Kontuak\Ports\Resource\PeriodicalMovement;

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

    /**
     * @param Request $request
     * @return PeriodicalMovement
     * @throws \Kontuak\Ports\Exception\EntityNotFound
     */
    public function execute(Request $request)
    {
        try {
            $response = new PeriodicalMovement(
                $this->source->get(Id::parse($request->id))
            );
        } catch (EntityNotFound $e) {
            throw new \Kontuak\Ports\Exception\EntityNotFound($e);
        }

        return $response;
    }
}