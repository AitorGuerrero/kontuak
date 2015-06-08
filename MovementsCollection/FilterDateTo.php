<?php
/**
 * Created by PhpStorm.
 * User: aitor.guerrero
 * Date: 29/5/15
 * Time: 16:04
 */

namespace Kontuak\MovementsCollection;


class FilterDateTo implements Filter
{
    /**
     * @var \DateTimeInterface
     */
    private $dateTo;

    public function __construct(\DateTimeInterface $dateTo)
    {
        $this->dateTo = $dateTo;
    }
}