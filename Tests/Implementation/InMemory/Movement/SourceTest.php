<?php

namespace Kontuak\Tests\Implementation\InMemory\Movement;

use Kontuak\Implementation\InMemory\Movement\Source;

class SourceTest extends \PHPUnit_Framework_TestCase
{
    use \Kontuak\Tests\Implementation\Movement\SourceTest;

    protected function setUp()
    {
        $this->source = new Source();
    }
}