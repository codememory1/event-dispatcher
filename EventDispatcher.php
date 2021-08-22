<?php

namespace Codememory\Components\Event;

use Codememory\Components\Event\Exceptions\EventExistException;
use Codememory\Components\Event\Exceptions\EventNotExistException;
use Codememory\Components\Event\Interfaces\EventBuilderInterface;
use Codememory\Components\Event\Interfaces\EventDispatcherInterface;
use JetBrains\PhpStorm\Pure;
use ReflectionException;

/**
 * Class EventDispatcher
 *
 * @package Codememory\Components\Event
 *
 * @author  Codememory
 */
class EventDispatcher implements EventDispatcherInterface
{

    /**
     * @var Event[]
     */
    private array $events = [];

    /**
     * @inheritDoc
     * @throws EventExistException
     * @throws Exceptions\EventNotImplementInterfaceException
     * @throws ReflectionException
     */
    public function addEvent(string $eventNamespace): EventBuilderInterface
    {

        if ($this->existEvent($eventNamespace)) {
            throw new EventExistException($eventNamespace);
        }

        $event = new Event($eventNamespace);

        $this->events[] = $event;

        return $event;

    }

    /**
     * @inheritDoc
     * @throws EventNotExistException
     * @throws Exceptions\ListenerAddedException
     * @throws Exceptions\ListenerNotExistException
     * @throws Exceptions\ListenerNotImplementInterfaceException
     */
    public function addEventListener(string $eventName, callable|string $listener): EventDispatcherInterface
    {

        $this->getEvent($eventName)->addListener($listener);

        return $this;

    }

    /**
     * @inheritDoc
     */
    #[Pure]
    public function existEvent(string $eventName): bool
    {

        return false !== $this->getEventIfExist($eventName);

    }

    /**
     * @inheritDoc
     * @throws EventNotExistException
     */
    public function getEvent(string $eventName): Event
    {

        if (false === $event = $this->getEventIfExist($eventName)) {
            throw new EventNotExistException($eventName);
        }

        return $event;

    }

    /**
     * @inheritDoc
     * @throws EventNotExistException
     * @throws ReflectionException
     */
    public function getEventListeners(string $eventName): array
    {

        return $this->getEvent($eventName)->getListeners();

    }

    /**
     * @param string $eventName
     *
     * @return bool|Event
     */
    #[Pure]
    private function getEventIfExist(string $eventName): bool|Event
    {

        foreach ($this->events as $event) {
            if ((null === $event->getAlias() && $event->getNamespace() === $eventName)
                || ($event->getAlias() === $eventName)) {
                return $event;
            }
        }

        return false;

    }

}