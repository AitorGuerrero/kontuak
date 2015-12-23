<?php

namespace Kontuak\Ports\Movement;

use Kontuak\Ports\Exception\InvalidArgument;
use Kontuak\Movement\Source;
use Kontuak\Ports\Movement\GetAll\Request;

class GetAll
{
    /** @var */
    private $source;

    public function __construct(Source $source)
    {
        $this->source = $source;
    }

    public $resource =[];
    public function execute(Request $request)
    {
        if(null === $request->limit) {
            throw new InvalidArgument('limit');
        }
        $movements = [];

        $collection = $this->source->collection();
        $this->paginate($request->page, $request->limit, $collection);

        $i = 1;
        while($collection->valid()) {
            $movement = $collection->current();
            $movements[] = $movement;
            $i++;
            if($i > $request->limit) {
                break;
            }
            $collection->next();
        }

        return $movements;
    }

    public function newRequest()
    {
        return new Request();
    }

    /**
     * @param $page
     * @param $limit
     * @param $collection
     */
    private function paginate($page, $limit, $collection)
    {
        if (!$page) {
            return;
        }
        $firstKey = ($page - 1) * $limit;
        for ($i = 0; $i < $firstKey; $i++) {
            $collection->next();
        }
    }
}