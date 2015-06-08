<?php

namespace Kontuak\Interactors\GetMovementsHistory;

use DateTime;
use Kontuak\Movement;
use Kontuak\MovementsCollection;
use Kontuak\MovementsCollection\FilterDateTo;
use Kontuak\MovementsCollection\Order;
use Kontuak\MovementsCollection\Page;

class UseCase
{
    /**
     * @var MovementsCollection
     */
    private $movementsCollection;
    /**
     * @var Transformer
     */
    private $transformer;
    /**
     * @var PeriodicalMovementsCollection
     */
    private $periodicalMovementsCollection;

    public function __construct(
        MovementsCollection $movementsCollection,
        PeriodicalMovementsCollection $periodicalMovementsCollection,
        Transformer $transformer
    ) {
        $this->movementsCollection = $movementsCollection;
        $this->transformer = $transformer;
        $this->periodicalMovementsCollection = $periodicalMovementsCollection;
    }

    public function execute(Request $request)
    {
        $toDate = new \DateTime($request->toDate);
        $page = new Page($request->page, $request->limit, Order::byDate());
        $filter = new FilterDateTo($toDate);
        $movements = $this->mergeMovements(
            $this->movementsCollection->find($filter, $page),
            $this->predictPeriodicalMovements($toDate)
        );
        $this->transformer->transform($movements);
    }

    public function mergeMovements($movements, $predictedMovements)
    {
        return usort(array_merge($movements, $predictedMovements), function(Movement $a, Movement $b) {
            return $a->date() === $b->date() ? 0 : $a->date() > $b->date() ? 1 : -1;
        });
    }

    /**
     * @param $toDate
     * @return array
     */
    private function predictPeriodicalMovements(DateTime $toDate)
    {
        $periodicalMovements = $this->periodicalMovementsCollection->find();
        $predictedMovements = [];
        foreach ($periodicalMovements as $periodicalMovement) {
            $predictedMovements = array_merge($predictedMovements, $periodicalMovement->generateTo($toDate));
        }
        return $predictedMovements;
    }
}