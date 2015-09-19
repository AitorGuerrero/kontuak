<?php

namespace Kontuak\Interactors\Movement\Create;

use Kontuak\Interactors\InvalidArgumentException;
use Kontuak\Interactors\SystemException;
use Kontuak\Movement;
use Kontuak\Period;

class UseCase
{
    /** @var Movement\Source */
    private $movementsSource;
    /** @var \DateTime */
    private $currentDateTime;
    /** @var Movement\Factory */
    private $movementFactory;

    public function __construct(
        Movement\Source $movementsSource,
        \DateTime $currentDateTime,
        Movement\Factory $movementFactory
    ) {
        $this->movementsSource = $movementsSource;
        $this->currentDateTime = $currentDateTime;
        $this->movementFactory = $movementFactory;
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
        $this->movementsSource->persist($movement);
    }

    private function createMovement(Request $request)
    {
        try {
            $movement = $this->movementFactory->make(
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