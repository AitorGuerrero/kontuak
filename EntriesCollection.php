<?php
/**
 * Created by PhpStorm.
 * User: aitor.guerrero
 * Date: 27/5/15
 * Time: 15:20
 */

namespace Kontuak;

interface EntriesCollection
{
    public function add(Entry $entry);
}