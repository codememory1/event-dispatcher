<?php

namespace Codememory\Components\Event;

use Codememory\Components\DateTime\DateTime;
use Codememory\Components\DateTime\Exceptions\InvalidTimezoneException;
use Codememory\Components\Event\Interfaces\EventDataInterface;
use Codememory\Components\Event\Interfaces\ListenerInterface;
use Codememory\Components\Profiling\Exceptions\BuilderNotCurrentSectionException;
use Codememory\Components\Profiling\ReportCreators\EventsReportCreator;
use Codememory\Components\Profiling\Resource;
use Codememory\Components\Profiling\Sections\Builders\EventsBuilder;
use Codememory\Components\Profiling\Sections\EventsSection;
use Codememory\Support\Str;
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
     * @throws BuilderNotCurrentSectionException
     * @throws InvalidTimezoneException
     * @throws ReflectionException
     */
    public function dispatch(EventDataInterface $event): void
    {

        $microTime = microtime(true);
        $backtrace = debug_backtrace();

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

        $this->profiling($event, $microTime, $this->getStackByClassName($backtrace[0], $backtrace));

    }

    /**
     * @param EventDataInterface $event
     * @param float              $microTime
     * @param array              $stack
     *
     * @return void
     * @throws InvalidTimezoneException
     * @throws BuilderNotCurrentSectionException
     */
    private function profiling(EventDataInterface $event, float $microTime, array $stack): void
    {

        $eventsReportCreator = new EventsReportCreator(null, new EventsSection(new Resource()));
        $eventsBuilder = new EventsBuilder();

        $eventsBuilder
            ->setEvent($event->getNamespace())
            ->setListeners(array_map(function (callable|string $listener) {
                return is_callable($listener) ? 'callback' : $listener;
            }, $event->getListeners()))
            ->setDemanded($stack['class'], $stack['method'])
            ->setCompleted((new DateTime())->format('Y-m-d H:i:s'))
            ->setLeadTime(round((microtime(true) - $microTime) * 1000));

        $eventsReportCreator->create($eventsBuilder);

    }

    /**
     * @param array $firstStack
     * @param array $debugBacktrace
     *
     * @return array
     */
    private function getStackByClassName(array $firstStack, array $debugBacktrace): array
    {

        $fullFilename = Str::trimToSymbol($firstStack['file'], '/', false);
        $filename = Str::trimAfterSymbol($fullFilename, '.', false);

        foreach ($debugBacktrace as $stack) {
            if (str_ends_with($stack['class'], $filename)) {
                return $stack;
            }
        }

        return [];

    }

}