<?php

namespace Kontuak\Tests\Implementation\InMemory\Movement;

use Kontuak\Implementation\Movement\Source;

class SourceTest extends \PHPUnit_Framework_TestCase
{
    use \Kontuak\Tests\Implementation\Movement\SourceTest;

    protected function setUp()
    {
        $this->idGenerator = new \Kontuak\Movement\Id\Generator();
        $this->source = new Source\InMemory();
        $this->timeStamp = new \DateTime('2015-01-01');
    }
}