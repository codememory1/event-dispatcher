<?php

namespace Codememory\Components\Event\Exceptions;

use JetBrains\PhpStorm\Pure;

/**
 * Class EventNotExistException
 *
 * @package Codememory\Components\Event\Exceptions
 *
 * @author  Codememory
 */
class EventNotExistException extends AbstractEventException
{

    /**
     * @param string $eventName
     */
    #[Pure]
    public function __construct(string $eventName)
    {

        parent::__construct(sprintf('Event %s does not exist', $eventName));

    }

}