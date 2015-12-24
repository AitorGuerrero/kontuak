<?php

namespace Kontuak;

use DateTime as SystemDateTime;

class DateTime extends SystemDateTime
{
    const ISO_DATE_FORMAT = 'Y-m-d';

    const ISO_DATE_TIME_FORMAT = 'Y-m-d H:i:s';

    public function isoDate()
    {
        return $this->format(self::ISO_DATE_FORMAT);
    }

    public function isoDateTime()
    {
        return $this->format(self::ISO_DATE_TIME_FORMAT);
    }
}
