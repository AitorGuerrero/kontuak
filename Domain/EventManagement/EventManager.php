<?php

namespace Kontuak\EventManagement;

class EventManager
{
    /** @var EventPublisher */
    private static $instance;
    /** @var EventPublisher[] */
    public $eventPublishers = [];

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
    }

    public function subscribe(Subscriber $subscriber, Subject $subject, $event, $handler)
    {
        foreach($this->eventPublishers as $publisher) {
            $publisher->subscribe($subscriber, $subject, $event, $handler);
        }
    }

    /**
     * @param EventPublisher $eventPublisher
     */
    public function addEventPublisher(EventPublisher $eventPublisher)
    {
        $this->eventPublishers[] = $eventPublisher;
    }
}
