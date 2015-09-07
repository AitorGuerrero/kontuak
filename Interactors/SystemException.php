<?php

namespace Kontuak\Interactors;

class SystemException extends InteractorException
{
    /** @var int */
    private $originalException;

    protected $message = 'System crashed';

    public function __construct($message, \Exception $originalException)
    {
        $this->message = $message;
        $this->originalException = $originalException;
    }

    public function originalException()
    {
        return $this->originalException;
    }
}