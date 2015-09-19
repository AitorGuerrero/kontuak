<?php

namespace Kontuak\Tests\Interactors\Movement\Coming;

use Kontuak\Implementation\InMemory\Movement\Factory;
use Kontuak\Implementation\InMemory\Movement\Source;
use Kontuak\Interactors\Movement\Coming\UseCase;
use Kontuak\Movement;

class Test extends \PHPUnit_Framework_TestCase
{
    const CURRENT_DATE_ISO = '2015-06-01';
    private $request;
    /** @var Factory */
    private $movementFactory;

    /** @var UseCase */
    private $useCase;
    /** @var Source */
    private $movementsSource;

    protected function setUp()
    {
        $this->movementsSource = new Source();
        $this->movementFactory = new Factory();
        $this->useCase = new UseCase(
            new Movement\History(
                $this->movementsSource,
                new Movement\TotalAmountCalculator($this->movementsSource)
            ),
            new \Kontuak\Implementation\Transformer\Movement(),
            new \DateTime(self::CURRENT_DATE_ISO)
        );
        $this->request = $this->useCase->newRequest();
        $this->request->limit = 100;
    }

    /**
     * @test
     */
    public function shouldNotReturnPastMovements()
    {
        $this->movementsSource->add($this->movementGenerator('2015-01-01'));
        $this->movementsSource->add($this->movementGenerator('2015-06-02'));

        $response = $this->useCase->execute($this->request);

        $this->assertEquals(1, count($response->movements));
    }

    /**
     * @test
     */
    public function shouldNotReturnCurrentDaysMovements()
    {
        $this->movementsSource->add($this->movementGenerator(self::CURRENT_DATE_ISO));
        $this->movementsSource->add($this->movementGenerator('2015-06-02'));

        $response = $this->useCase->execute($this->request);

        $this->assertEquals(1, count($response->movements));
    }

    /**
     * @test
     * @group PIS
     */
    public function shouldSetCalculatedAmount()
    {
        $this->movementsSource->add($this->movementGenerator('2015-05-01', 1));
        $this->movementsSource->add($this->movementGenerator('2015-06-02', 1));
        $this->movementsSource->add($this->movementGenerator('2015-06-10', 1));

        $response = $this->useCase->execute($this->request);

        $this->assertEquals(3, $response->movements[0]['totalAmount']);
        $this->assertEquals(2, $response->movements[1]['totalAmount']);
    }

    public function whenLimitedShouldReturnOnlydesiredMovements()
    {
        $this->movementsSource->add($this->movementGenerator());
        $this->movementsSource->add($this->movementGenerator());
        $this->movementsSource->add($this->movementGenerator());

        $this->request->limit = 2;
        $response = $this->useCase->execute($this->request);

        $this->assertEquals(2, count($response->movements));
    }

    private function movementGenerator($isoDate = '2015-06-01', $amount = 10)
    {
        return $this->movementFactory->make(
            $this->movementsSource->newId(),
            $amount,
            'Concept',
            new \DateTime($isoDate),
            new \DateTime('2015-01-01')
        );
    }
}