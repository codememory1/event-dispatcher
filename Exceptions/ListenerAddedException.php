<?php

namespace Codememory\Components\Event\Exceptions;

use JetBrains\PhpStorm\Pure;

/**
 * Class ListenerAddedException
 *
 * @package Codememory\Components\Event\Exceptions
 *
 * @author  Codememory
 */
class ListenerAddedException extends AbstractListenerException
{

    /**
     * @param string $eventNamespace
     * @param string $listener
     */
    #[Pure]
    public function __construct(string $eventNamespace, string $listener)
    {

        parent::__construct(sprintf('The %s listener has already been added to the %s event', $listener, $eventNamespace));

    }

}