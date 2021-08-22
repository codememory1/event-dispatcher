<?php

namespace Codememory\Components\Event\Exceptions;

use JetBrains\PhpStorm\Pure;

/**
 * Class ListenerNotExistException
 *
 * @package Codememory\Components\Event\Exceptions
 *
 * @author  Codememory
 */
class ListenerNotExistException extends AbstractListenerException
{

    /**
     * @param string $listener
     */
    #[Pure]
    public function __construct(string $listener)
    {

        parent::__construct(sprintf('Listener under namespace %s does not exist', $listener));

    }

}