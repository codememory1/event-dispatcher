<?php

namespace Codememory\Components\Event\Exceptions;

use JetBrains\PhpStorm\Pure;

/**
 * Class EventExistException
 *
 * @package Codememory\Components\Event\Exceptions
 *
 * @author  Codememory
 */
class EventExistException extends AbstractEventException
{

    /**
     * @param string $eventName
     */
    #[Pure]
    public function __construct(string $eventName)
    {

        parent::__construct(sprintf('Event %s already exists', $eventName));

    }

}