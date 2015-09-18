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
        $this->useCase = new UseCase(
            new Movement\History($this->source, $this->totalAmountService),
            new \Kontuak\Implementation\Transformer\Movement(),
            $this->today
        );
        $this->request = new Request();
        $this->request->limit = 5;
        $this->movementFactory = new Factory();
    }

    /**
     * @test
     * @group PIS
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

        $this->assertEquals(3, count($response->amounts));
        $this->assertEquals($movement2, $response->amounts[0]['movement']);
        $this->assertEquals($movement3, $response->amounts[1]['movement']);
        $this->assertEquals($movement1, $response->amounts[2]['movement']);
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

        $this->assertEquals(1, count($response->amounts));
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

        $this->assertTrue(empty($response->amounts));
    }

    /**
     * @test
     */
    public function shouldReturnMovementsOfToday()
    {
        $movement = $this->generateMovement(-50, self::TODAY_IDO);
        $this->source->add($movement);
        $response = $this->useCase->execute($this->request);

        $this->assertEquals(1, count($response->amounts));
    }

    /**
     * @test
     */
    public function shouldReturnTheMovementsTotalAmount()
    {
        $this->source->add($this->generateMovement(30, '2015-08-01'));
        $this->source->add($this->generateMovement(-40, '2015-08-05'));
        $response = $this->useCase->execute($this->request);

        $this->assertEquals(-10, $response->amounts[0]['totalAmount']);
        $this->assertEquals(30, $response->amounts[1]['totalAmount']);
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

        $this->assertEquals(80, $response->amounts[0]['totalAmount']);
        $this->assertEquals(-20, $response->amounts[1]['totalAmount']);
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