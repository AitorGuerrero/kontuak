<?php

namespace Ports\Movement\Remove;

use Kontuak\Exception\Source\EntityNotFound;
use Kontuak\Adapters\InMemory\Movement\Source;
use Kontuak\Ports\Movement\Remove\UseCase;
use Kontuak\Ports\Movement\Remove\Request;
use Kontuak\Movement;

class Test extends \PHPUnit_Framework_TestCase
{
    const INVALID_ID = 'b12721fc-55a2-406d-8121-86f4c2868866';
    const MALFORMED_ID = 'malformed_id';
    const MOVEMENT_ID = '1782c153-c48f-4cf3-a24a-21f2957461c9';

    /** @var Source */
    private $source;
    /** @var \Kontuak\Ports\Movement\Remove\UseCase */
    private $useCase;
    /** @var \Kontuak\Ports\Movement\Remove\Request */
    private $request;


    protected function setUp()
    {
        $this->source = new Source();
        $this->useCase = new UseCase($this->source);
        $this->request = new Request();
    }

    /**
     * @test
     */
    public function whenTheMovementDoesNotExistsShouldThrowAnException()
    {
        $this->setExpectedException('\Kontuak\Ports\Movement\Remove\MovementDoesNotExistsException');
        $this->request->id = self::INVALID_ID;
        $this->useCase->execute($this->request);
    }

    /**
     * @test
     */
    public function shouldRemoveTheMovementFromTheSource()
    {
        $id = new Movement\Id(self::MOVEMENT_ID);
        $this->source->add(new Movement(
            $id,
            100,
            'a',
            new \DateTime(),
            new \DateTime()
        ));
        $this->request->id = self::MOVEMENT_ID;
        $this->useCase->execute($this->request);

        try {
            $this->source->get($id);
            $this->assertTrue(false);
        } catch (EntityNotFound $e) {
            $this->assertTrue(true);
        }
    }
}