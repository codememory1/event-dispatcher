<?php

namespace Codememory\Components\Event\Commands;

use Codememory\Components\Console\Command;
use Codememory\Components\Event\Utils;
use Codememory\Components\Finder\Find;
use Codememory\Support\Str;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ListenerListCommand
 *
 * @package Codememory\Components\Event\Commands
 *
 * @author  Codememory
 */
class ListenerListCommand extends Command
{

    /**
     * @inheritdoc
     */
    protected ?string $command = 'event:listener:list';

    /**
     * @inheritdoc
     */
    protected ?string $description = 'Get a list of all listeners';

    /**
     * @inheritDoc
     */
    protected function handler(InputInterface $input, OutputInterface $output): int
    {

        $utils = new Utils();
        $finder = new Find();
        $table = new Table($output);

        $finder
            ->setPathForFind($utils->getPathWithListeners())
            ->file()
            ->byRegex(sprintf('%s.php$', $utils->getListenerSuffix()));

        $listeners = [];

        foreach ($finder->get() as $listenerPath) {
            $fullFilename = Str::trimToSymbol($listenerPath, '/', false);
            $filename = Str::trimAfterSymbol($fullFilename, '.', false);
            $namespace = $utils->getEventNamespace() . '\\' . $filename;

            $listeners[] = [
                $listenerPath,
                $filename,
                $namespace
            ];
            $listeners[] = new TableSeparator();
        }

        unset($listeners[array_key_last($listeners)]);

        $this->io->text($this->tags->yellowText('Table of all listeners'));

        $table
            ->setHeaders(['path', 'name', 'namespace'])
            ->setRows($listeners)
            ->setStyle('box')
            ->render();

        return Command::SUCCESS;

    }

}