<?php

namespace Kontuak\Ports\Movement;

use Kontuak\Movement\Collection;
use Kontuak\Movement\Source;

class GetAll
{
    /** @var */
    private $source;

    public function __construct(Source $source)
    {
        $this->source = $source;
    }

    public $resource =[];

    /**
     * @param int $limit
     * @param int $page
     * @return array
     */
    public function execute($limit, $page)
    {
        $collection = $this->source->collection();
        $this->goToPage($page, $limit, $collection);
        $movements = $this->extractOnePage($limit, $collection);

        return $movements;
    }

    /**
     * @param $page
     * @param $limit
     * @param $collection
     */
    private function goToPage($page, $limit, $collection)
    {
        if (!$page) {
            return;
        }
        $firstKey = ($page - 1) * $limit;
        for ($i = 0; $i < $firstKey; $i++) {
            $collection->next();
        }
    }

    /**
     * @param $limit
     * @param $collection
     * @return array
     */
    private function extractOnePage($limit, Collection $collection)
    {
        $movements = [];
        for ($i = 0; $i < $limit && $collection->valid(); $i++, $collection->next()) {
            $movements[] = $collection->current();
        }

        return $movements;
    }
}