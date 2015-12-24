<?php

namespace Kontuak\EventManagement;

class EventPublisher
{
    /** @var EventPublisher */
    private static $instance;
    /** @var EventListener[] */
    private $eventListeners = [];

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
        foreach (self::getInstance()->eventListeners as $eventListener) {
            $eventListener->consume($event);
        }
    }

    /**
     * @param EventListener $eventListener
     */
    public function addEventListener(EventListener $eventListener)
    {
        $this->eventListeners[] = $eventListener;
    }
}
