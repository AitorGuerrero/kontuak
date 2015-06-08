<?php

namespace Kontuak\Interactors\CreateNewEntry;

class UseCase
{
    /**
     * @var EntriesCollection
     */
    private $expendituresCollection;

    public function __construct(EntriesCollection $expendituresCollection)
    {
        $this->expendituresCollection = $expendituresCollection;
    }

    public function execute(Request $request)
    {
        $expenditure = new Expenditure($request->amount, $request->concept);
        $this->expendituresCollection->add($expenditure);
    }
}