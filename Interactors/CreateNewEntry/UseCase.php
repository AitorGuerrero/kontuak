<?php

namespace Kontuak\Interactors\CreateNewEntry;

use Kontuak\EntriesCollection;
use Kontuak\Entry;
use Kontuak\Interactors\InvalidArgumentException;
use Kontuak\Interactors\SystemException;
use Kontuak\Movement;
use Kontuak\MovementId;
use Kontuak\MovementsCollection;
use Kontuak\MovementsSource;

class UseCase
{
    /**
     * @var MovementsSource
     */
    private $movementsSource;
    /**
     * @var \DateTimeInterface
     */
    private $currentDateTime;

    public function __construct(MovementsSource $movementsSource, \DateTimeInterface $currentDateTime)
    {
        $this->movementsSource = $movementsSource;
        $this->currentDateTime = $currentDateTime;
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
            $entry = new Movement(
                new MovementId(),
                abs($request->amount),
                $request->concept,
                new \DateTime($request->date),
                $this->currentDateTime
            );
            $this->movementsSource->add($entry);
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