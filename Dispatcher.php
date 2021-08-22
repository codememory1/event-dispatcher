<?php

namespace Codememory\Components\Event;

use Codememory\Components\Event\Interfaces\EventDataInterface;
use Codememory\Components\Event\Interfaces\ListenerInterface;
use ReflectionException;

/**
 * Class Dispatcher
 *
 * @package Codememory\Components\Event
 *
 * @author  Codememory
 */
class Dispatcher
{

    /**
     * @param EventDataInterface $event
     *
     * @throws ReflectionException
     */
    public function dispatch(EventDataInterface $event): void
    {

        /** @var ListenerInterface|callable $listener */
        foreach ($event->getListeners() as $listener) {
            $eventObject = $event->getReflector()->newInstance(...$event->getParameters());

            if (is_callable($listener)) {
                call_user_func($listener, $eventObject);
            } else {
                $listenerObject = new $listener();

                $listenerObject->{ListenerInterface::HANDLE_METHOD}($eventObject);
            }
        }

    }

}