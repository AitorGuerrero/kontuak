<?php

namespace Kontuak\Interactors\CreateNewEntry;

use Kontuak\EntriesCollection;
use Kontuak\Entry;
use Kontuak\Interactors\InvalidArgumentException;
use Kontuak\Interactors\SystemException;

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

    /**
     * @param Request $request
     * @throws InvalidArgumentException
     * @throws SystemException
     * @return Response
     */
    public function execute(Request $request)
    {
        $dateTime = new \DateTime();
        try {
            $expenditure = new Entry($request->amount, $request->concept, $dateTime);
            $this->expendituresCollection->add($expenditure);
        } catch (\Kontuak\InvalidArgumentException $e) {
            throw new InvalidArgumentException();
        } catch (\Exception $e) {
            throw new SystemException();
        }

        $response = new Response();
        $response->entry = [
            'id' => $expenditure->id()->serialize()
        ];
        return $response;
    }
}