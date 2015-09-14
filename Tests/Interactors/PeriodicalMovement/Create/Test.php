<?php

namespace Kontuak\Tests\Interactors\PeriodicalMovement\Create;

use Kontuak\Interactors\PeriodicalMovement\Create;
use Kontuak\Implementation\InMemory;
use Kontuak\PeriodicalMovement;
use Kontuak\Implementation;

class Test extends \PHPUnit_Framework_TestCase
{
    /** @var Create\Request */
    private $request;
    /** @var Create\UseCase */
    private $useCase;
    private $amount = 10;
    private $concept = 'Pus';
    private $periodType = Create\Request::TYPE_DAYS;
    private $periodAmount = 4;
    /** @var PeriodicalMovement\Source */
    private $source;

    protected function setUp()
    {
        $idGenerator = new PeriodicalMovement\Id\Generator();
        $this->source = new Implementation\PeriodicalMovement\Source\InMemory();
        $this->request = new Create\Request();
        $this->request->id = $idGenerator->generate()->serialize();
        $this->request->amount = $this->amount;
        $this->request->concept = $this->concept;
        $this->request->periodType = $this->periodType;
        $this->request->periodAmount = $this->periodAmount;
        $this->request->starts = '2015-08-01';
        $this->useCase = new Create\UseCase($this->source);
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
}