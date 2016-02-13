<?php

namespace Kontuak;

interface CurrentDateTimeProvider
{
    /**
     * @return \DateTimeImmutable
     */
    public function getCurrentDateTime();
}
