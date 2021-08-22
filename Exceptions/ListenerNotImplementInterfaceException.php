<?php

namespace Codememory\Components\Event\Exceptions;

use Codememory\Components\Event\Interfaces\ListenerInterface;
use JetBrains\PhpStorm\Pure;

/**
 * Class ListenerNotImplementInterfaceException
 *
 * @package Codememory\Components\Event\Exceptions
 *
 * @author  Codememory
 */
class ListenerNotImplementInterfaceException extends AbstractListenerException
{

    /**
     * @param string $listener
     */
    #[Pure]
    public function __construct(string $listener)
    {

        parent::__construct(sprintf(
            'The %s listener does not implement the %s interface',
            $listener,
            ListenerInterface::class
        ));

    }

}