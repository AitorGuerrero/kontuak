<?php

namespace Kontuak;

use Kontuak\MovementsCollection\Page;

interface MovementsCollection
{
    /**
     * @param []Kontuak\MovementsCollection\Filter $filter
     * @param Page $page
     * @return mixed
     */
    public function find($filter, Page $page);
}