<?php

namespace Interactors\Movement\Update;

use Kontuak\Implementation\InMemory\Movement\Source;
use Kontuak\Movement;
use Kontuak\Interactors\Movement\Update\UseCase;
use Kontuak\Interactors\Movement\Update\Request;

class Test extends \PHPUnit_Framework_TestCase
{
    /** @var Source */
    public $movementsSource;
    /** @var UseCase */
    public $useCase;
    /** @var Request */
    public $request;

    const MOVEMENT_ID = 'asda';

    const NEW_AMOUNT = 120;

    const NEW_CONCEPT = 'Pus';

    const NEW_DATE = '2015-05-03';

    protected function setUp()
    {
        $this->movementsSource = new Source();
        $this->movementsSource->add(
            new Movement(
                new Movement\Id(self::MOVEMENT_ID),
                100,
                'Pis',
                new \DateTime('2015-05-05'),
                new \DateTime('2015-05-01 01:02:03')
            )
        );

        $this->useCase = new UseCase($this->movementsSource);
        $this->request = new Request();
        $this->request->id = self::MOVEMENT_ID;
        $this->request->amount = self::NEW_AMOUNT;
        $this->request->concept = self::NEW_CONCEPT;
        $this->request->date = self::NEW_DATE;
    }
    /**
     * @test
     * @throws \Kontuak\Interactors\MovementDoesNotExistException
     */
    public function whenTheMovementDoesNotExistShouldThrowAnException()
    {
        $this->setExpectedException('\Kontuak\Interactors\MovementDoesNotExistException');
        $this->request->id = 'inexistentId';

        $this->useCase->execute($this->request);
    }

    /**
     * @test
     */
    public function shouldModifyTheMovement()
    {
        $this->useCase->execute($this->request);

        $movement = $this->movementsSource->collection()->findById(new Movement\Id(self::MOVEMENT_ID));

        $this->assertEquals(self::NEW_AMOUNT, $movement->amount());
        $this->assertEquals(self::NEW_CONCEPT, $movement->concept());
        $this->assertEquals(self::NEW_DATE, $movement->date()->format('Y-m-d'));
    }
}