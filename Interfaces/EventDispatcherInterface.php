<?php

namespace Codememory\Components\Event\Interfaces;

use Codememory\Components\Event\Event;

/**
 * Interface EventDispatcherInterface
 *
 * @package Codememory\Components\Event\Interfaces
 *
 * @author  Codememory
 */
interface EventDispatcherInterface
{

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Add a new non-existent event
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @param string $eventNamespace
     *
     * @return EventBuilderInterface
     */
    public function addEvent(string $eventNamespace): EventBuilderInterface;

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Add a listener to a specific event
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @param string          $eventName
     * @param string|callable $listener
     *
     * @return EventDispatcherInterface
     */
    public function addEventListener(string $eventName, string|callable $listener): EventDispatcherInterface;

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Check for event existence by alias or namespace
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @param string $eventName
     *
     * @return bool
     */
    public function existEvent(string $eventName): bool;

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Get event by alias or namespace
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @param string $eventName
     *
     * @return Event
     */
    public function getEvent(string $eventName): Event;

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Get listeners for a specific event
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @param string $eventName
     *
     * @return array
     */
    public function getEventListeners(string $eventName): array;

}