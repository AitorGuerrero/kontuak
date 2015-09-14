<?php

namespace Kontuak;

use Kontuak\UUIDv4\MalformedUUIDV4Exception;

class UUIDv4
{
    const MATCH_REG_EXP = '/^[a-z0-9]{8}-[a-z0-9]{4}-4[a-z0-9]{3}-[89ab][a-z0-9]{3}-[a-z0-9]{12}$/';

    /** @var string */
    private $serialized;

    /**
     * @param $serialized
     * @throws MalformedUUIDV4Exception
     */
    public function __construct($serialized)
    {
        if ($this->isMalformed($serialized)) {
            throw new MalformedUUIDV4Exception($serialized, 'Should comply '.self::MATCH_REG_EXP);
        }
        $this->serialized = $serialized;
    }

    public function serialize()
    {
        return $this->serialized;
    }

    private function isMalformed($string)
    {
        return 0 === preg_match(self::MATCH_REG_EXP, $string);
    }
}