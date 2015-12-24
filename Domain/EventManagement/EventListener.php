<?php

namespace Kontuak\EventManagement;

interface EventListener
{
    public function consume(Event $event);
}
