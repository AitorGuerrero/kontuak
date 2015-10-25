<?php

namespace Kontuak\Ports\Movement\GetAll;

use Kontuak\Ports\Exception\InvalidArgument;
use Kontuak\Movement\Source;

class UseCase
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
        $response = new Response();
        $response->movements = [];

        $collection = $this->source->collection();
        $this->paginate($request->page, $request->limit, $collection);

        $i = 1;
        while($movement = $collection->next()) {
            $response->movements[] = $movement;
            $i++;
            if($i > $request->limit) {
                break;
            }
        }

        return $response;
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