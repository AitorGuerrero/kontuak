<?php

namespace Kontuak\Tests\Movement;

use Kontuak\Movement\Id;

class IdTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function ifTheSerializedIsAMalformedUUIDShouldThrowAnException()
    {
        $this->setExpectedException('\Kontuak\UUIDv4\MalformedUUIDV4Exception');
        $malformedUUID = 'MalformedUUID';

        new Id($malformedUUID);
    }

    /**
     * @test
     */
    public function whenGivenVersionOtherThan4ShouldThrowAnException()
    {
        $this->setExpectedException('\Kontuak\UUIDv4\MalformedUUIDV4Exception');
        $malformedUUID = '082ce378-1736-195c-b821-e010294704ca';

        new Id($malformedUUID);
    }

    /**
     * @test
     */
    public function shouldCreateFromCorrectUUIDv4()
    {
        $correctUUID = '082ce378-1736-495c-b821-e010294704ca';
        $id = new Id($correctUUID);

        $this->assertEquals($correctUUID, $id->serialize());
    }
}