<?php

namespace Codememory\Components\Event\Interfaces;

/**
 * Interface EventInterface
 *
 * @package Codememory\Components\Event\Interfaces
 *
 * @author  Codememory
 */
interface EventInterface
{

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * The method should return an array of listeners for the current event
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @return array
     */
    public function getListeners(): array;

}