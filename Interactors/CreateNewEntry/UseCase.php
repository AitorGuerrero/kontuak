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
    private $entryCollection;

    public function __construct(EntriesCollection $entryCollection)
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
        $dateTime = new \DateTime();
        try {
            $entry = new Entry($request->amount, $request->concept, $dateTime);
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