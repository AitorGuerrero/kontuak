<?php

namespace Kontuak\Interactors\CreateNewExpenditure;

use Kontuak\Expenditure;
use Kontuak\ExpendituresCollection;
use Kontuak\Interactors\InvalidArgumentException;
use Kontuak\Interactors\SystemException;
use Kontuak\Movement;
use Kontuak\MovementId;
use Kontuak\MovementsCollection;

class UseCase
{
    /**
     * @var ExpendituresCollection
     */
    private $expendituresCollection;

    public function __construct(MovementsCollection $expendituresCollection)
    {
        $this->expendituresCollection = $expendituresCollection;
    }

    public function execute(Request $request)
    {
        try {
            $expenditure = new Movement(
                new MovementId(),
                -abs($request->amount),
                $request->concept,
                new \DateTime($request->dateTimeSerialized)
            );
            $this->expendituresCollection->add($expenditure);
        } catch (\Kontuak\InvalidArgumentException $e) {
            throw new InvalidArgumentException();
        } catch (\Exception $e) {
            throw new SystemException();
        }

        $response = new Response();
        $response->expenditure = [
            'id' => $expenditure->id()->serialize()
        ];

        return $response;
    }
}