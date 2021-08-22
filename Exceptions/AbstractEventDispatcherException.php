<?php

namespace Codememory\Components\Event\Exceptions;

use ErrorException;
use JetBrains\PhpStorm\Pure;

/**
 * Class AbstractEventDispatcherException
 *
 * @package Codememory\Components\Event\Exceptions
 *
 * @author  Codememory
 */
abstract class AbstractEventDispatcherException extends ErrorException
{

    /**
     * @param string|null $message
     */
    #[Pure]
    public function __construct(?string $message = null)
    {

        parent::__construct($message ?: '');

    }

}