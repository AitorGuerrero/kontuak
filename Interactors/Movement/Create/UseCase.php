<?php

namespace Kontuak\Interactors\Movement\Create;

use Kontuak\Interactors\InvalidArgumentException;
use Kontuak\Interactors\SystemException;
use Kontuak\Movement;
use Kontuak\Period;
use Kontuak\PeriodicalMovement;

class UseCase
{
    /** @var Movement\Source */
    private $movementsSource;
    /** @var \DateTimeInterface */
    private $currentDateTime;
    /** @var PeriodicalMovement\Source */
    private $periodicalMovementSource;
    /** @var PeriodicalMovement\Id\Generator */
    private $periodicalMovementGenerator;
    private $periodTyopeMapping = [
        Request::PERIOD_TYPE_DAYS => Period::TYPE_DAY,
        Request::PERIOD_TYPE_MONTHS => Period::TYPE_MONTH_DAY,
    ];

    public function __construct(
        Movement\Source $movementsSource,
        PeriodicalMovement\Source $periodicalMovementSource,
        PeriodicalMovement\Id\Generator $periodicalMovementGenerator,
        \DateTimeInterface $currentDateTime
    ) {
        $this->movementsSource = $movementsSource;
        $this->currentDateTime = $currentDateTime;
        $this->periodicalMovementSource = $periodicalMovementSource;
        $this->periodicalMovementGenerator = $periodicalMovementGenerator;
    }

    /**
     * @param Request $request
     * @throws InvalidArgumentException
     * @throws SystemException
     * @return Response
     */
    public function execute(Request $request)
    {
        $response = new Response();
        if ($request->isPeriodical) {
            $periodicalMovement = $this->createPeriodicalMovement($request);
            $response->periodicalMovementId = $periodicalMovement->id()->serialize();
            $response->periodicalMovementAmount = $periodicalMovement->period()->amount();
            $response->periodicalMovementType = array_flip($this->periodTyopeMapping)
                [$periodicalMovement->period()->type()];
        }
        $movement = $this->createMovement($request);
        $response->movementId = $movement->id()->serialize();
        $response->movementAmount = $movement->amount();
        $response->movementConcept = $movement->concept();
        $response->movementDate = $movement->date()->format('Y-m-d');
        $response->movementCreated = $movement->created()->format('Y-m-d H:i:s');

        return $response;
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
            Period::factory($this->periodTyopeMapping[$request->periodType], $request->periodAmount)
        );
        $this->periodicalMovementSource->add($periodicalMovement);

        return $periodicalMovement;
    }

    private function createMovement($request)
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