<?php

namespace Kontuak\Interactors\PeriodicalMovement\Create;

use Kontuak\Period;
use Kontuak\PeriodicalMovement\Factory;
use Kontuak\PeriodicalMovement\Id;
use Kontuak\PeriodicalMovement\Source;

class UseCase
{

    /** @var Source */
    private $source;
    /** @var Factory */
    private $factory;
    private $mapPeriodTypeToDomain = [
        Request::PERIOD_TYPE_DAYS => Period::TYPE_DAY,
        Request::PERIOD_TYPE_MONTHS => Period::TYPE_MONTH_DAY,
    ];

    public function __construct(Factory $factory, Source $source)
    {
        $this->source = $source;
        $this->factory = $factory;
    }

    /**
     * @return Request
     */
    public function newRequest()
    {
        return new Request();
    }

    /**
     * @param Request $request
     */
    public function execute(Request $request)
    {
        $this->source->add(
            $this->factory->make(
                new Id($request->id),
                $request->amount,
                $request->concept,
                new \DateTime($request->starts),
                Period::factory(
                    $this->mapPeriodTypeToDomain[$request->periodType],
                    $request->periodAmount
                )
            )
        );
    }
}