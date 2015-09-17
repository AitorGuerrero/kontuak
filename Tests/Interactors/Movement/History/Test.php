<?php

namespace Kontuak\Tests\Interactors\Movement\History;

use Kontuak\Implementation\InMemory\Movement\Factory;
use Kontuak\Implementation\Movement\Source;
use Kontuak\Interactors\Movement\History\Request;
use Kontuak\Interactors\Movement\History\UseCase;
use Kontuak\Movement;

/**
 * Class Test
 * @package Kontuak\Tests\Interactors\Movement\History
 */
class Test extends \PHPUnit_Framework_TestCase
{
    const TODAY_IDO = '2016-01-01';
    /** @var Factory */
    private $movementFactory;
    /** @var \DateTime */
    private $today;
    /** @var Movement\Source */
    private $source;
    /** @var UseCase */
    private $useCase;
    /** @var Request */
    private $request;
    /** @var Movement\TotalAmountCalculator */
    private $totalAmountService;
    /** @var Movement\Id\Generator */
    private $idGenerator;

    protected function setUp()
    {
        $this->idGenerator = new Movement\Id\Generator();
        $this->today = new \DateTime(self::TODAY_IDO);
        $this->source = new Source\InMemory();
        $this->totalAmountService = new Movement\TotalAmountCalculator($this->source);
        $this->useCase = new UseCase($this->source, $this->totalAmountService, $this->today);
        $this->request = new Request();
        $this->request->limit = 5;
        $this->movementFactory = new Factory();
    }

    /**
     * @test
     *
     */
    public function shouldReturnMovementsOrderedByDateDesc()
    {
        $movement1 = $this->generateMovement(30, '2015-08-01');
        $movement2 = $this->generateMovement(100, '2015-08-05');
        $movement3 = $this->generateMovement(-50, '2015-08-04');
        $this->source->add($movement1);
        $this->source->add($movement2);
        $this->source->add($movement3);
        $response = $this->useCase->execute($this->request);

        $this->assertEquals(3, count($response->movements));
        $this->assertEquals($movement2->id()->serialize(), $response->movements[0]['id']);
        $this->assertEquals($movement3->id()->serialize(), $response->movements[1]['id']);
        $this->assertEquals($movement1->id()->serialize(), $response->movements[2]['id']);
    }

    /**
     * @test
     */
    public function shouldReturnMovementsMainData()
    {
        $amount = 30;
        $concept = 'A';
        $date = '2015-08-01';
        $movement = $this->generateMovement($amount, $date, $concept);
        $this->source->add($movement);
        $response = $this->useCase->execute($this->request);

        $this->assertEquals($amount, $response->movements[0]['amount']);
        $this->assertEquals($concept, $response->movements[0]['concept']);
        $this->assertEquals($date, $response->movements[0]['date']);
    }

    /**
     * @test
     */
    public function shouldReturnMovementsBeforeToday()
    {
        $beforeDateStr = '2015-08-01';
        $movement = $this->generateMovement(100, $beforeDateStr);
        $this->source->add($movement);
        $response = $this->useCase->execute($this->request);

        $this->assertEquals(1, count($response->movements));
        $this->assertEquals($beforeDateStr, $response->movements[0]['date']);
    }

    /**
     * @test
     */
    public function shouldNotReturnMovementsAfterToday()
    {
        $beforeDateStr = '2020-08-01';
        $movement = $this->generateMovement(100, $beforeDateStr);
        $this->source->add($movement);
        $response = $this->useCase->execute($this->request);

        $this->assertTrue(empty($response->movements));
    }

    /**
     * @test
     */
    public function shouldReturnMovementsOfToday()
    {
        $movement = $this->generateMovement(-50, self::TODAY_IDO);
        $this->source->add($movement);
        $response = $this->useCase->execute($this->request);

        $this->assertEquals(1, count($response->movements));
        $this->assertEquals($this->today->format('Y-m-d'), $response->movements[0]['date']);
    }

    /**
     * @test
     */
    public function shouldReturnTheMovementsTotalAmount()
    {
        $this->source->add($this->generateMovement(30, '2015-08-01'));
        $this->source->add($this->generateMovement(-40, '2015-08-05'));
        $response = $this->useCase->execute($this->request);

        $this->assertEquals(-10, $response->movements[0]['totalAmount']);
        $this->assertEquals(30, $response->movements[1]['totalAmount']);
    }

    /**
     * @test
     */
    public function whenThereAreMoreMovementsThanLimitShouldGetCorrectTotalAmount()
    {
        $this->source->add($this->generateMovement(30, '2015-08-01'));
        $this->source->add($this->generateMovement(100, '2015-08-05'));
        $this->source->add($this->generateMovement(-50, '2015-08-04'));
        $this->request->limit = 2;
        $response = $this->useCase->execute($this->request);

        $this->assertEquals(80, $response->movements[0]['totalAmount']);
        $this->assertEquals(-20, $response->movements[1]['totalAmount']);
    }

    /**
     * @test
     */
    public function shouldReturnRequiredData()
    {
        $movementId = $this->idGenerator->generate();
        $amount = -50;
        $concept = 'C';
        $date = new \DateTime('2015-08-04');
        $created = new \DateTime('2015-01-01');
        $this->source->add($this->movementFactory->make($movementId, $amount, $concept, $date, $created));
        $this->request->limit = 2;
        $response = $this->useCase->execute($this->request);

        $this->assertEquals($movementId->serialize(), $response->movements[0]['id']);
        $this->assertEquals($amount, $response->movements[0]['amount']);
        $this->assertEquals($concept, $response->movements[0]['concept']);
        $this->assertEquals($date->format('Y-m-d'), $response->movements[0]['date']);
        $this->assertEquals($created->format('Y-m-d h:i:s'), $response->movements[0]['created']);
        $this->assertEquals(-50, $response->movements[0]['totalAmount']);
    }

    /**
     * @expectedException \Kontuak\Interactors\InvalidArgumentException
     * @test
     */
    public function whenLimitIsNotANumberShouldThrowAnException()
    {
        $this->request->limit = null;
        $this->useCase->execute($this->request);
    }

    public function generateMovement($amount = 10, $date = '2015-01-01', $concept = 'Concept')
    {
        return $this->movementFactory->make(
            $this->idGenerator->generate(),
            $amount,
            $concept,
            new \DateTime($date),
            new \DateTime('2015-01-01')
        );
    }

}