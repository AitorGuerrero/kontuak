<?php

namespace Kontuak\Interactors\Movement\Create;

use Kontuak\Movement\Transformer as MovementTransformer;
use Kontuak\Interactors\InvalidArgumentException;
use Kontuak\Interactors\SystemException;
use Kontuak\Movement;
use Kontuak\Period;
use Kontuak\PeriodicalMovement;
use Kontuak\PeriodicalMovement\MovementsGenerator;

class UseCase
{
    /** @var Movement\Source */
    private $movementsSource;
    /** @var \DateTime */
    private $currentDateTime;
    /** @var PeriodicalMovement\Source */
    private $periodicalMovementSource;
    /** @var PeriodicalMovement\Id\Generator */
    private $periodicalMovementGenerator;
    private $periodTypeMapping = [
        Request::PERIOD_TYPE_DAYS => Period::TYPE_DAY,
        Request::PERIOD_TYPE_MONTHS => Period::TYPE_MONTH_DAY,
    ];
    /** @var MovementsGenerator */
    private $movementsGenerator;
    /** @var MovementTransformer */
    private $transformer;

    public function __construct(
        Movement\Source $movementsSource,
        PeriodicalMovement\Source $periodicalMovementSource,
        PeriodicalMovement\Id\Generator $periodicalMovementGenerator,
        MovementsGenerator $movementsGenerator,
        \DateTime $currentDateTime,
        MovementTransformer $transformer
    ) {
        $this->movementsSource = $movementsSource;
        $this->currentDateTime = $currentDateTime;
        $this->periodicalMovementSource = $periodicalMovementSource;
        $this->periodicalMovementGenerator = $periodicalMovementGenerator;
        $this->movementsGenerator = $movementsGenerator;
        $this->transformer = $transformer;
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws InvalidArgumentException
     * @throws SystemException
     */
    public function execute(Request $request)
    {
        $movement = $this->createMovement($request);
        if ($request->isPeriodical) {
            $periodicalMovement = $this->createPeriodicalMovement($request);
                [$periodicalMovement->period()->type()];
            $movement->assignToPeriodicalMovement($periodicalMovement);
        }
        $this->movementsSource->persist($movement);

        return $this->transformer->toResource($movement);
    }

    /**
     * @param Request $request
     * @return PeriodicalMovement
     */
    private function createPeriodicalMovement(Request $request)
    {
        $periodicalMovement = new PeriodicalMovement(
            $this->periodicalMovementGenerator->generate(),
            $request->amount,
            $request->concept,
            new \DateTime($request->date),
            Period::factory($this->periodTypeMapping[$request->periodType], $request->periodAmount)
        );
        $this->periodicalMovementSource->add($periodicalMovement);

        return $periodicalMovement;
    }

    private function createMovement(Request $request)
    {
        try {
            $movement = new Movement(
                new Movement\Id($request->id),
                $request->amount,
                $request->concept,
                new \DateTime($request->date),
                $this->currentDateTime
            );
            $this->movementsSource->add($movement);
        } catch (\Kontuak\InvalidArgumentException $e) {
            throw new InvalidArgumentException($e->getMessage());
        } catch (\Exception $e) {
            throw new SystemException('Persistence Layer failed', $e);
        }
        return $movement;
    }
}