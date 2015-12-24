<?php

namespace Kontuak\Ports\Movement;

use Kontuak\IsoDateTime;
use Kontuak\Movement;
use Kontuak\Movement\Id;
use Kontuak\Movement\Source;

class Put
{

    /** @var Source */
    private $source;
    /** @var \DateTime */
    private $currentTimeStamp;

    public function __construct(Source $source, IsoDateTime $currentTimeStamp)
    {
        $this->source = $source;
        $this->currentTimeStamp = $currentTimeStamp;
    }

    /**
     * @param $id
     * @param $amount
     * @param $concept
     * @param $isoDate
     */
    public function execute($id, $amount = null, $concept = null, $isoDate = null)
    {
        $movement = $this->source->collection()->byId(Id::parse($id))->current();
        if(!$movement) {
            $this->source->add(
                $this->makeNewMovement(
                    $id,
                    $amount,
                    $concept,
                    $isoDate
                )
            );
        } else {
            $this->updateAmountIfNotNull($amount, $movement);
            $this->updateConceptIfNotNull($concept, $movement);
            $this->updateDateIfNotNull($isoDate, $movement);
        }
    }

    /**
     * @param $stringId
     * @param $amount
     * @param $concept
     * @param $isoDate
     * @return Movement
     */
    private function makeNewMovement($stringId, $amount, $concept, $isoDate)
    {
        return new Movement(
            Id::parse($stringId),
            $amount,
            $concept,
            new IsoDateTime($isoDate),
            $this->currentTimeStamp
        );
    }

    /**
     * @param $amount
     * @param $movement
     */
    private function updateAmountIfNotNull($amount, Movement $movement)
    {
        if (!is_null($amount)) {
            $movement->updateAmount($amount);
        }
    }

    /**
     * @param $concept
     * @param $movement
     */
    private function updateConceptIfNotNull($concept, Movement $movement)
    {
        if (!is_null($concept)) {
            $movement->updateConcept($concept);
        }
    }

    /**
     * @param $isoDate
     * @param $movement
     */
    private function updateDateIfNotNull($isoDate, Movement $movement)
    {
        if (!is_null($isoDate)) {
            $movement->updateDate(new IsoDateTime($isoDate));
        }
    }
}