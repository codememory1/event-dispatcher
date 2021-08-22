<?php

namespace Codememory\Components\Event\Commands;

use Codememory\Components\Console\Command;
use Codememory\Components\Event\Interfaces\ListenerInterface;
use Codememory\Components\Event\Utils;
use Codememory\FileSystem\File;
use Codememory\FileSystem\Interfaces\FileInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class MakeEventCommand
 *
 * @package Codememory\Components\Event\Commands
 *
 * @author  Codememory
 */
class MakeEventCommand extends Command
{

    /**
     * @inheritDoc
     */
    protected ?string $command = 'make:event';

    /**
     * @inheritDoc
     */
    protected ?string $description = 'Create an event and one listener with this event';

    /**
     * @inheritDoc
     */
    protected function wrapArgsAndOptions(): Command
    {

        $this->addArgument('name', InputArgument::REQUIRED, 'Event name without suffix');

        return $this;

    }

    /**
     * @inheritDoc
     */
    protected function handler(InputInterface $input, OutputInterface $output): int
    {

        $filesystem = new File();
        $utils = new Utils();

        // Event
        $eventName = $input->getArgument('name');
        $fullEventName = $eventName . $utils->getEventSuffix();
        $eventNamespace = $utils->getEventNamespace() . '\\' . $fullEventName;
        $fullEventPath = $utils->getPathWithEvents() . $fullEventName . '.php';

        // Listener
        $fullListenerName = $eventName . $utils->getListenerSuffix();
        $listenerNamespace = $utils->getListenerNamespace() . '\\' . $fullListenerName;
        $fullListenerPath = $utils->getPathWithListeners() . $fullListenerName . '.php';

        $eventStub = $this->getBuildEvent(
            $this->getEventStub($filesystem),
            $utils->getEventNamespace(),
            $fullEventName,
            $listenerNamespace,
            $fullListenerName
        );
        $listenerStub = $this->getBuildListener(
            $this->getListenerStub($filesystem),
            $utils->getListenerNamespace(),
            $fullListenerName,
            $eventNamespace,
            $fullEventName
        );

        if ($filesystem->exist($fullEventPath)) {
            $this->io->error(sprintf('An event named %s already exists', $eventName));

            return Command::FAILURE;
        }

        if ($filesystem->exist($fullListenerPath)) {
            $this->io->error(sprintf('The %s listener already exists', $eventName));

            return Command::FAILURE;
        }

        if (!$filesystem->exist($utils->getPathWithEvents())) {
            $filesystem->mkdir($utils->getPathWithEvents(), 0777, true);
        }

        if (!$filesystem->exist($utils->getPathWithListeners())) {
            $filesystem->mkdir($utils->getPathWithListeners(), 0777, true);
        }

        file_put_contents($fullEventPath, $eventStub);
        file_put_contents($fullListenerPath, $listenerStub);

        $this->io->success([
            sprintf('Event %s created successfully', $eventName),
            sprintf('Event path: %s', $fullEventPath),
            sprintf('Listener path: %s', $fullListenerPath)
        ]);

        return Command::SUCCESS;

    }

    /**
     * @param FileInterface $filesystem
     *
     * @return string
     */
    private function getEventStub(FileInterface $filesystem): string
    {

        return file_get_contents($filesystem->getRealPath('/vendor/codememory/event-dispatcher/Commands/Stubs/EventStub.stub'));

    }

    /**
     * @param FileInterface $filesystem
     *
     * @return string
     */
    private function getListenerStub(FileInterface $filesystem): string
    {

        return file_get_contents($filesystem->getRealPath('/vendor/codememory/event-dispatcher/Commands/Stubs/ListenerStub.stub'));

    }

    /**
     * @param string $stub
     * @param string $namespace
     * @param string $className
     * @param string $listenerNamespace
     * @param string $listenerName
     *
     * @return string
     */
    private function getBuildEvent(string $stub, string $namespace, string $className, string $listenerNamespace, string $listenerName): string
    {

        return str_replace([
            '{namespace}',
            '{className}',
            '{listenerNamespace}',
            '{listenerName}'
        ], [
            $namespace,
            $className,
            $listenerNamespace,
            $listenerName
        ], $stub);

    }

    /**
     * @param string $stub
     * @param string $namespace
     * @param string $className
     * @param string $eventNamespace
     * @param string $eventName
     *
     * @return string
     */
    private function getBuildListener(string $stub, string $namespace, string $className, string $eventNamespace, string $eventName): string
    {

        return str_replace([
            '{namespace}',
            '{className}',
            '{eventNamespace}',
            '{eventName}',
            '{handleMethod}'
        ], [
            $namespace,
            $className,
            $eventNamespace,
            $eventName,
            ListenerInterface::HANDLE_METHOD
        ], $stub);

    }

}