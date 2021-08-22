<?php

namespace Codememory\Components\Event\Interfaces;

/**
 * Interface EventBuilderInterface
 *
 * @package Codememory\Components\Event\Interfaces
 *
 * @author  Codememory
 */
interface EventBuilderInterface
{

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Set an alias for the event by which the event can be received
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @param string $alias
     *
     * @return EventBuilderInterface
     */
    public function setAlias(string $alias): EventBuilderInterface;

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Add listener to event, listener must be either namespace implementing
     * ListenerInterface or callback
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @param string|callable $listener
     *
     * @return EventBuilderInterface
     */
    public function addListener(string|callable $listener): EventBuilderInterface;

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Set parameters that will be passed to the construct of each
     * listener for a given event
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @param array $parameters
     *
     * @return EventBuilderInterface
     */
    public function setParameters(array $parameters): EventBuilderInterface;

}