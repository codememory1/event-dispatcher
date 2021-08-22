<?php

namespace Codememory\Components\Event\Exceptions;

use Codememory\Components\Event\Interfaces\EventInterface;
use JetBrains\PhpStorm\Pure;

/**
 * Class EventNotImplementInterfaceException
 *
 * @package Codememory\Components\Event\Exceptions
 *
 * @author  Codememory
 */
class EventNotImplementInterfaceException extends AbstractEventException
{

    /**
     * @param string $eventNamespace
     */
    #[Pure]
    public function __construct(string $eventNamespace)
    {

        parent::__construct(sprintf(
            'The %s event does not implement the %s interface',
            $eventNamespace,
            EventInterface::class
        ));

    }

}