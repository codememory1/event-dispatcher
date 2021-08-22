<?php

namespace Codememory\Components\Event;

use Codememory\Components\Event\Exceptions\EventNotImplementInterfaceException;
use Codememory\Components\Event\Exceptions\ListenerAddedException;
use Codememory\Components\Event\Exceptions\ListenerNotExistException;
use Codememory\Components\Event\Exceptions\ListenerNotImplementInterfaceException;
use Codememory\Components\Event\Interfaces\EventBuilderInterface;
use Codememory\Components\Event\Interfaces\EventDataInterface;
use Codememory\Components\Event\Interfaces\EventInterface;
use Codememory\Components\Event\Interfaces\ListenerInterface;
use ReflectionClass;
use ReflectionException;

/**
 * Class Event
 *
 * @package Codememory\Components\Event
 *
 * @author  Codememory
 */
class Event implements EventBuilderInterface, EventDataInterface
{

    /**
     * @var string
     */
    private string $eventNamespace;

    /**
     * @var ReflectionClass
     */
    private ReflectionClass $eventReflector;

    /**
     * @var string|null
     */
    private ?string $alias = null;

    /**
     * @var array
     */
    private array $listeners = [];

    /**
     * @var array
     */
    private array $parameters = [];

    /**
     * @param string $eventNamespace
     *
     * @throws EventNotImplementInterfaceException
     * @throws ReflectionException
     */
    public function __construct(string $eventNamespace)
    {

        $eventReflector = new ReflectionClass($eventNamespace);

        if (!$eventReflector->implementsInterface(EventInterface::class)) {
            throw new EventNotImplementInterfaceException($eventNamespace);
        }

        $this->eventNamespace = $eventNamespace;
        $this->eventReflector = $eventReflector;

    }

    /**
     * @inheritDoc
     */
    public function getNamespace(): string
    {

        return $this->eventNamespace;

    }

    /**
     * @inheritDoc
     */
    public function getReflector(): ReflectionClass
    {

        return $this->eventReflector;

    }

    /**
     * @inheritDoc
     */
    public function setAlias(string $alias): EventBuilderInterface
    {

        $this->alias = $alias;

        return $this;

    }

    /**
     * @inheritDoc
     * @throws ListenerAddedException
     * @throws ListenerNotExistException
     * @throws ListenerNotImplementInterfaceException
     */
    public function addListener(callable|string $listener): EventBuilderInterface
    {

        if (is_string($listener)) {
            if (!class_exists($listener)) {
                throw new ListenerNotExistException($listener);
            }

            $reflector = new ReflectionClass($listener);

            if (!$reflector->implementsInterface(ListenerInterface::class)) {
                throw new ListenerNotImplementInterfaceException($listener);
            }

            if (in_array($listener, $this->listeners)) {
                throw new ListenerAddedException($this->eventNamespace, $listener);
            }

            $this->listeners[] = new $listener();
        } else {
            $this->listeners[] = $listener;
        }

        return $this;

    }

    /**
     * @inheritDoc
     */
    public function setParameters(array $parameters): EventBuilderInterface
    {

        $this->parameters = $parameters;

        return $this;

    }

    /**
     * @inheritDoc
     */
    public function getAlias(): ?string
    {

        return $this->alias;

    }

    /**
     * @inheritDoc
     * @throws ListenerAddedException
     * @throws ListenerNotExistException
     * @throws ListenerNotImplementInterfaceException
     * @throws ReflectionException
     */
    public function getListeners(): array
    {

        /** @var EventInterface $eventObject */
        $eventObject = $this->eventReflector->newInstance(...$this->getParameters());

        foreach ($eventObject->getListeners() as $listener) {
            $this->addListener($listener);
        }

        return $this->listeners;

    }

    /**
     * @inheritDoc
     */
    public function getParameters(): array
    {

        return $this->parameters;

    }

}