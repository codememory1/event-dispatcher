<?php

namespace Codememory\Components\Event;

use Codememory\Components\Event\Interfaces\EventDataInterface;
use Codememory\Components\Event\Interfaces\ListenerInterface;
use Codememory\Components\Profiling\Exceptions\BuilderNotCurrentSectionException;
use Codememory\Components\Profiling\ReportCreators\EventsReportCreator;
use Codememory\Components\Profiling\Resource;
use Codememory\Components\Profiling\Sections\Builders\EventsBuilder;
use Codememory\Components\Profiling\Sections\EventsSection;
use ReflectionException;
use Spatie\Backtrace\Backtrace;
use Spatie\Backtrace\Frame;

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
     * @throws BuilderNotCurrentSectionException
     * @throws ReflectionException
     */
    public function dispatch(EventDataInterface $event): void
    {

        $microTime = microtime(true);

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

        $this->profiling($event, $microTime, Backtrace::create()->frames()[1]);

    }

    /**
     * @param EventDataInterface $event
     * @param float              $microTime
     * @param Frame              $stack
     *
     * @return void
     * @throws BuilderNotCurrentSectionException
     */
    private function profiling(EventDataInterface $event, float $microTime, Frame $stack): void
    {

        $eventsReportCreator = new EventsReportCreator(null, new EventsSection(new Resource()));
        $eventsBuilder = new EventsBuilder();

        $eventsBuilder
            ->setEvent($event->getNamespace())
            ->setListeners(array_map(function (callable|string $listener) {
                return is_callable($listener) ? 'callback' : $listener;
            }, $event->getListeners()))
            ->setDemanded($stack->class, $stack->method)
            ->setLeadTime(round((microtime(true) - $microTime) * 1000));

        $eventsReportCreator->create($eventsBuilder);

    }

}