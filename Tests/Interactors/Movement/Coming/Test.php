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

    /** @var Movement\Id\Generator */
    private $movementIdGenerator;

    protected function setUp()
    {
        $this->movementsSource = new InMemory();
        $this->useCase = new UseCase($this->movementsSource, new \DateTime(self::CURRENT_DATE_ISO));
        $this->movementIdGenerator = new Movement\Id\Generator();
    }

    /**
     * @test
     */
    public function shouldNotReturnPastMovements()
    {
        $this->movementsSource->add($this->movementGenerator('2015-01-01'));
        $this->movementsSource->add($this->movementGenerator('2015-06-02'));

        $response = $this->useCase->execute();

        $this->assertEquals(1, count($response->movements));
    }

    /**
     * @test
     */
    public function shouldNotReturnCurrentDaysMovements()
    {
        $this->movementsSource->add($this->movementGenerator(self::CURRENT_DATE_ISO));
        $this->movementsSource->add($this->movementGenerator('2015-06-02'));

        $response = $this->useCase->execute();

        $this->assertEquals(1, count($response->movements));
    }

    private function movementGenerator($isoDate = '2015-06-01')
    {
        return new Movement(
            $this->movementIdGenerator->generate(),
            10,
            'Concept',
            new \DateTime($isoDate),
            new \DateTime('2015-01-01')
        );
    }
}