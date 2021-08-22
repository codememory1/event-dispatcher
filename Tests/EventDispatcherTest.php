<?php

namespace Codememory\Components\Event\Tests;

use Codememory\Components\Event\Event;
use Codememory\Components\Event\EventDispatcher;
use Codememory\Components\Event\Exceptions\EventExistException;
use Codememory\Components\Event\Exceptions\EventNotExistException;
use Codememory\Components\Event\Exceptions\EventNotImplementInterfaceException;
use Codememory\Components\Event\Exceptions\ListenerNotExistException;
use Codememory\Components\Event\Exceptions\ListenerNotImplementInterfaceException;
use Codememory\Components\Event\Interfaces\EventDispatcherInterface;
use Codememory\Components\Event\Tests\Classes\TestEvent;
use Codememory\Components\Event\Tests\Classes\TestListener;
use PHPUnit\Framework\TestCase;
use ReflectionException;

/**
 * Class EventDispatcherTest
 *
 * @package Codememory\Components\Event\Tests
 *
 * @author  Codememory
 */
final class EventDispatcherTest extends TestCase
{

    /**
     * @var EventDispatcherInterface
     */
    private EventDispatcherInterface $eventDispatcher;

    /**
     * @return void
     */
    public function setUp(): void
    {

        $this->eventDispatcher = new EventDispatcher();

    }

    /**
     * @return void
     * @throws EventExistException
     * @throws EventNotImplementInterfaceException
     * @throws ReflectionException
     */
    public function testAddEvent(): void
    {

        $createdEvent = $this->eventDispatcher->addEvent(TestEvent::class);

        $this->assertInstanceOf(Event::class, $createdEvent);

    }

    /**
     * @return void
     * @throws EventExistException
     * @throws EventNotImplementInterfaceException
     * @throws ReflectionException
     */
    public function testAddExistEvent(): void
    {

        $this->eventDispatcher->addEvent(TestEvent::class);

        $this->expectException(EventExistException::class);

        $this->eventDispatcher->addEvent(TestEvent::class);

    }

    /**
     * @return void
     * @throws EventNotExistException
     */
    public function testGetNotExistEvent(): void
    {

        $this->expectException(EventNotExistException::class);

        $this->eventDispatcher->getEvent(TestEvent::class);

    }

    /**
     * @return void
     * @throws EventExistException
     * @throws EventNotExistException
     * @throws EventNotImplementInterfaceException
     * @throws ReflectionException
     */
    public function testGetEvent(): void
    {

        $this->eventDispatcher->addEvent(TestEvent::class);

        $this->assertInstanceOf(Event::class, $this->eventDispatcher->getEvent(TestEvent::class));

    }

    /**
     * @return void
     * @throws EventExistException
     * @throws EventNotExistException
     * @throws EventNotImplementInterfaceException
     * @throws ReflectionException
     */
    public function testGetEventByAlias(): void
    {

        $this->eventDispatcher->addEvent(TestEvent::class)
            ->setAlias('test-event');

        $this->assertInstanceOf(Event::class, $this->eventDispatcher->getEvent('test-event'));

    }

    /**
     * @return void
     * @throws EventExistException
     * @throws EventNotImplementInterfaceException
     * @throws ReflectionException
     */
    public function testExistEvent(): void
    {

        $this->eventDispatcher->addEvent(TestEvent::class);

        $this->assertTrue($this->eventDispatcher->existEvent(TestEvent::class));
        $this->assertFalse($this->eventDispatcher->existEvent('test-event'));

    }

    /**
     * @return void
     * @throws EventExistException
     * @throws EventNotImplementInterfaceException
     * @throws ReflectionException
     */
    public function testAddNotExistListener(): void
    {

        $createdEvent = $this->eventDispatcher->addEvent(TestEvent::class);

        $this->expectException(ListenerNotExistException::class);

        $createdEvent->addListener('Listener');

    }

    /**
     * @return void
     * @throws EventExistException
     * @throws EventNotImplementInterfaceException
     * @throws ReflectionException
     */
    public function testAddListenerNotImplementingInterface(): void
    {

        $createdEvent = $this->eventDispatcher->addEvent(TestEvent::class);

        $this->expectException(ListenerNotImplementInterfaceException::class);

        $createdEvent->addListener((new class {

        })::class);

    }

    /**
     * @return void
     * @throws EventExistException
     * @throws EventNotExistException
     * @throws EventNotImplementInterfaceException
     * @throws ReflectionException
     */
    public function testGetEventListeners(): void
    {

        $this->eventDispatcher->addEvent(TestEvent::class)
            ->addListener(TestListener::class);

        $this->assertEquals([new TestListener()], $this->eventDispatcher->getEventListeners(TestEvent::class));

    }

}