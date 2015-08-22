<?php

namespace Kontuak\Tests\Interactors\CreateAPeriodicalExpenditure;

use Kontuak\Implementation\InMemory\EntityId;
use Kontuak\Implementation\InMemory\PeriodicalMovementCollection;
use Kontuak\Interactors\CreateAPeriodicalExpenditure\Request;
use Kontuak\Interactors\CreateAPeriodicalExpenditure\UseCase;

class CreateAPeriodicalExpenditureTest extends \PHPUnit_Framework_TestCase
{
    /** @var Request */
    private $request;
    /** @var UseCase */
    private $useCase;
    private $amount = -10;
    private $concept = 'Pus';
    private $periodType = Request::TYPE_DAYS;
    private $periodAmount = 4;
    /** @var PeriodicalMovementCollection */
    private $collection;

    protected function setUp()
    {
        $this->collection = new PeriodicalMovementCollection();
        $this->request = new Request($this->amount, $this->concept, $this->periodType, $this->periodAmount);
        $this->useCase = new UseCase($this->collection);
    }

    /**
     * @expectedException \Kontuak\Interactors\InvalidArgumentException
     * @test
     */
    public function whenPeriodTypeDoesNotExistsShouldThrowAException()
    {
        $this->request->periodType = 'Pene';
        $this->useCase->execute($this->request);
    }

    /**
     * @test
     */
    public function shouldSavePeriodicalMovementCorrectly()
    {
        $response = $this->useCase->execute($this->request);
        $savedMovement = $this->collection->find(new EntityId($response->periodicalMovement['id']));

        $this->assertEquals($this->amount, $savedMovement->amount());
        $this->assertEquals($this->concept, $savedMovement->concept());
        $this->assertInstanceOf('\Kontuak\Period\DaysPeriod', $savedMovement->period());
        $this->assertEquals($this->periodAmount, $savedMovement->period()->amount());
    }
}