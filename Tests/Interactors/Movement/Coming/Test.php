<?php

namespace Kontuak\Tests\Interactors\Movement\Coming;

use Kontuak\Implementation\Movement\Source\InMemory;
use Kontuak\Interactors\Movement\Coming\UseCase;
use Kontuak\Movement;

class Test extends \PHPUnit_Framework_TestCase
{
    private $useCase;

    private $movementsSource;

    const CURRENT_DATE_ISO = '2015-06-01';

    protected function setUp()
    {
        $this->movementsSource = new InMemory();
        $this->useCase = new UseCase($this->movementsSource, new \DateTime(self::CURRENT_DATE_ISO));
    }

    /**
     * @test
     */
    public function shouldNotReturnPastMovements()
    {
        $movementId1 = '5373d7bb-0e42-4e11-830a-a7c5a5113598';
        $movementId2 = '5373d7bb-0e42-4e11-830a-a7c5a5113599';
        $movement1 = new Movement(
            new Movement\Id($movementId1),
            10,
            'Concept',
            new \DateTime('2015-01-01'),
            new \DateTime('2015-01-01')
        );
        $movement2 = new Movement(
            new Movement\Id($movementId2),
            10,
            'Concept',
            new \DateTime('2015-06-02'),
            new \DateTime('2015-01-01')
        );
        $this->movementsSource->add($movement1);
        $this->movementsSource->add($movement2);

        $response = $this->useCase->execute();

        $this->assertEquals(1, count($response->movements));
    }

    /**
     * @test
     */
    public function shouldNotReturnCurrentDaysMovements()
    {
        $movementId1 = '5373d7bb-0e42-4e11-830a-a7c5a5113598';
        $movementId2 = '5373d7bb-0e42-4e11-830a-a7c5a5113599';
        $movement1 = new Movement(
            new Movement\Id($movementId1),
            10,
            'Concept',
            new \DateTime(self::CURRENT_DATE_ISO),
            new \DateTime('2015-01-01')
        );
        $movement2 = new Movement(
            new Movement\Id($movementId2),
            10,
            'Concept',
            new \DateTime('2015-06-02'),
            new \DateTime('2015-01-01')
        );
        $this->movementsSource->add($movement1);
        $this->movementsSource->add($movement2);

        $response = $this->useCase->execute();

        $this->assertEquals(1, count($response->movements));
    }
}