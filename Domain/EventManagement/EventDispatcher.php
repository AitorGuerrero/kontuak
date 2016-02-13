<?php

namespace Kontuak\EventManagement;

interface EventDispatcher
{
    public function dispatch(Event $event);
}
