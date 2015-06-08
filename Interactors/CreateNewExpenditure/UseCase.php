<?php

namespace kontuak\Interactors\CreateNewExpenditure;

use Kontuak\Expenditure;
use Kontuak\ExpendituresCollection;

class UseCase
{
    /**
     * @var ExpendituresCollection
     */
    private $expendituresCollection;

    public function __construct(ExpendituresCollection $expendituresCollection)
    {
        $this->expendituresCollection = $expendituresCollection;
    }

    public function execute(CreateNewExpenditureRequest $request)
    {
        $expenditure = new Expenditure($request->amount, $request->concept);
        $this->expendituresCollection->add($expenditure);
    }
}