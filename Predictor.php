<?php

namespace Kontuak;

class Predictor
{
    public function predict($from, $to)
    {
        $periodicalMovements = $this->periodicalMovementsCollection->getAll();
        $predictedMovements = [];
        foreach($periodicalMovements as $periodicalMovement) {
            $dates = $this->predictDates($periodicalMovement->period(), $from, $to);
            $predictedMovements = array_merge($predictedMovements, $this->populateMovements($dates, $periodicalMovement));
        }
    }

    private function predictDates(Period $period, \DateTimeInterface $from, \DateTimeInterface $to)
    {
        $dates = [];
        $date = $from;
        while ($date < $to) {
            $date = $period->next($date);
            $dates[] = $date;
        }

        return $dates;
    }

    private function populateMovements($dates, $periodicalMovement)
    {
        $movements = [];
        foreach ($dates as $date) {
            $movements[] = $periodicalMovement->newMovement($date);
        }
    }

}