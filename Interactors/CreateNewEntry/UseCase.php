<?php

namespace Kontuak\Interactors\CreateNewEntry;

use Kontuak\EntriesCollection;
use Kontuak\Entry;
use Kontuak\Interactors\InvalidArgumentException;
use Kontuak\Interactors\SystemException;
use Kontuak\Movement;
use Kontuak\MovementsCollection;

class UseCase
{
    /**
     * @var EntriesCollection
     */
    private $entryCollection;

    public function __construct(MovementsCollection $entryCollection)
    {
        $this->entryCollection = $entryCollection;
    }

    /**
     * @param Request $request
     * @throws InvalidArgumentException
     * @throws SystemException
     * @return Response
     */
    public function execute(Request $request)
    {
        try {
            $entry = new Movement(abs($request->amount), $request->concept, new \DateTime($request->date));
            $this->entryCollection->add($entry);
        } catch (\Kontuak\InvalidArgumentException $e) {
            throw new InvalidArgumentException();
        } catch (\Exception $e) {
            throw new SystemException();
        }

        $response = new Response();
        $response->entry = [
            'id' => $entry->id()->serialize()
        ];
        return $response;
    }
}