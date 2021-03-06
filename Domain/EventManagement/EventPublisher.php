<?php

namespace Kontuak\EventManagement;

class EventPublisher
{
    /** @var EventPublisher */
    private static $instance;
    /** @var EventDispatcher[] */
    private $eventDispatchers = [];

    /**
     * EventPublisher constructor.
     */
    private function __construct() {}

    /**
     * @return EventPublisher
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param Event $event
     */
    static function publish(Event $event)
    {
        foreach (self::getInstance()->eventDispatchers as $eventDispatcher) {
            $eventDispatcher->dispatch($event);
        }
    }

    /**
     * @param EventDispatcher $eventListener
     */
    public function addEventDispatcher(EventDispatcher $eventListener)
    {
        $this->eventDispatchers[] = $eventListener;
    }
}
