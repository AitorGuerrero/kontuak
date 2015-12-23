<?php

namespace Kontuak\Ports\Movement;

use Kontuak\Ports\Exception\EntityNotFound;
use Kontuak\Ports\Resource;
use Kontuak\Movement;

class GetOne
{
    /** @var Movement\Source */
    private $source;

    public function __construct(
        Movement\Source $source
    ) {
        $this->source = $source;
    }

    /**
     * @param int $id
     * @return Resource\Movement
     * @throws EntityNotFound
     */
    public function execute($id)
    {
        try {
            $movement = $this->source->get(Movement\Id::parse($id));
        } catch (\Kontuak\Exception\Source\EntityNotFound $e) {
            throw new EntityNotFound();
        }

        return new Resource\Movement($movement);
    }
}