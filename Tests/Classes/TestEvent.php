<?php

namespace Codememory\Components\Event\Tests\Classes;

use Codememory\Components\Event\Interfaces\EventInterface;

/**
 * Class TestEvent
 *
 * @package Codememory\Components\Event\Tests\Classes
 *
 * @author  Codememory
 */
class TestEvent implements EventInterface
{

    /**
     * @inheritDoc
     */
    public function getListeners(): array
    {

        return [];

    }

}