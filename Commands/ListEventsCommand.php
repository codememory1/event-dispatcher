<?php

namespace Codememory\Components\Event\Commands;

use Codememory\Components\Console\Command;
use Codememory\Components\Event\Event;
use Codememory\Components\Event\Exceptions\EventNotImplementInterfaceException;
use Codememory\Components\Event\Utils;
use Codememory\Components\Finder\Find;
use Codememory\Support\Str;
use ReflectionException;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ListEventsCommand
 *
 * @package Codememory\Components\Event\Commands
 *
 * @author  Codememory
 */
class ListEventsCommand extends Command
{

    /**
     * @inheritDoc
     */
    protected ?string $command = 'event:list';

    /**
     * @inheritDoc
     */
    protected ?string $description = 'Get a list of events';

    /**
     * @inheritDoc
     * @throws EventNotImplementInterfaceException
     * @throws ReflectionException
     */
    protected function handler(InputInterface $input, OutputInterface $output): int
    {

        $utils = new Utils();
        $finder = new Find();
        $table = new Table($output);

        $finder
            ->setPathForFind($utils->getPathWithEvents())
            ->file()
            ->byRegex(sprintf('%s.php$', $utils->getEventSuffix()));

        $events = [];

        foreach ($finder->get() as $eventPath) {
            $fullFilename = Str::trimToSymbol($eventPath, '/', false);
            $filename = Str::trimAfterSymbol($fullFilename, '.', false);
            $namespace = $utils->getEventNamespace() . '\\' . $filename;

            $event = new Event($namespace);

            $events[] = [
                $eventPath,
                $filename,
                implode(PHP_EOL, $event->getListeners())
            ];
            $events[] = new TableSeparator();
        }

        unset($events[array_key_last($events)]);

        $this->io->text($this->tags->yellowText('Events table and their information'));

        $table
            ->setHeaders(['path', 'name', 'listeners'])
            ->setRows($events)
            ->setStyle('box')
            ->render();

        return Command::SUCCESS;

    }

}