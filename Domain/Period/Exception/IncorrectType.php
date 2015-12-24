<?php

namespace Kontuak\Period\Exception;

use Kontuak\Exception;

class IncorrectType extends Exception
{
    /** @var string */
    private $type;

    public function __construct($type)
    {
        parent::__construct();
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function type()
    {
        return $this->type;
    }
}