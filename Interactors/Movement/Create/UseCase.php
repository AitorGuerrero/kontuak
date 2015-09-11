<?php

namespace Kontuak\Interactors\Movement\Create;

use Kontuak\Interactors\InvalidArgumentException;
use Kontuak\Interactors\SystemException;
use Kontuak\Movement;

class UseCase
{
    /**
     * @var Movement\Source
     */
    private $movementsSource;
    /**
     * @var \DateTimeInterface
     */
    private $currentDateTime;

    public function __construct(Movement\Source $movementsSource, \DateTimeInterface $currentDateTime)
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
                new Movement\Id($request->id),
                $request->amount,
                $request->concept,
                new \DateTime($request->date),
                $this->currentDateTime
            );
            $this->movementsSource->add($entry);
        } catch (\Kontuak\InvalidArgumentException $e) {
            throw new InvalidArgumentException($e->getMessage());
        } catch (\Exception $e) {
            throw new SystemException('Persistence Layer failed', $e);
        }

        $response = new Response();
        $response->movement = [
            'id' => $entry->id()->serialize()
        ];
        return $response;
    }
}