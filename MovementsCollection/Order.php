<?php

namespace Kontuak\MovementsCollection;

class Order
{
    const SUBJECT_DATE = 'date';

    private $orderSubject;

    private function __construct($orderSubject)
    {
        $this->orderSubject = $orderSubject;
    }

    public static function byDate()
    {
        return new self(self::SUBJECT_DATE);
    }

    public function subject()
    {
        return $this->subject();
    }
}