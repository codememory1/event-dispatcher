<?php

namespace Codememory\Components\Event;

use Codememory\Components\Caching\Exceptions\ConfigPathNotExistException;
use Codememory\Components\Configuration\Config;
use Codememory\Components\Configuration\Exceptions\ConfigNotFoundException;
use Codememory\Components\Configuration\Interfaces\ConfigInterface;
use Codememory\Components\Environment\Exceptions\EnvironmentVariableNotFoundException;
use Codememory\Components\Environment\Exceptions\ParsingErrorException;
use Codememory\Components\Environment\Exceptions\VariableParsingErrorException;
use Codememory\Components\GlobalConfig\GlobalConfig;
use Codememory\FileSystem\File;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Class Utils
 *
 * @package Codememory\Components\Event
 *
 * @author  Codememory
 */
class Utils
{

    /**
     * @var ConfigInterface
     */
    private ConfigInterface $config;

    /**
     * @throws ConfigPathNotExistException
     * @throws ConfigNotFoundException
     * @throws EnvironmentVariableNotFoundException
     * @throws ParsingErrorException
     * @throws VariableParsingErrorException
     */
    public function __construct()
    {

        $config = new Config(new File());

        $this->config = $config->open(GlobalConfig::get('event-dispatcher.configName'), $this->defaultConfig());

    }

    /**
     * @return string
     */
    public function getPathWithEvents(): string
    {

        return trim($this->config->get('pathWithEvents'), '/') . '/';

    }

    /**
     * @return string
     */
    public function getEventNamespace(): string
    {

        return trim($this->config->get('eventNamespace'), '\\');

    }

    /**
     * @return string|null
     */
    public function getEventSuffix(): ?string
    {

        return $this->config->get('eventSuffix');

    }

    /**
     * @return string
     */
    public function getPathWithListeners(): string
    {

        return trim($this->config->get('pathWithListeners'), '/') . '/';

    }

    /**
     * @return string
     */
    public function getListenerNamespace(): string
    {

        return trim($this->config->get('listenerNamespace'), '\\');

    }

    /**
     * @return string|null
     */
    public function getListenerSuffix(): ?string
    {

        return $this->config->get('listenerSuffix');

    }

    /**
     * @return array
     */
    #[ArrayShape([
        'pathWithEvents'    => "mixed",
        'eventNamespace'    => "mixed",
        'eventSuffix'       => "mixed",
        'pathWithListeners' => "mixed",
        'listenerNamespace' => "mixed",
        'listenerSuffix'    => "mixed"
    ])]
    private function defaultConfig(): array
    {

        return [
            'pathWithEvents'    => GlobalConfig::get('event-dispatcher.pathWithEvents'),
            'eventNamespace'    => GlobalConfig::get('event-dispatcher.eventNamespace'),
            'eventSuffix'       => GlobalConfig::get('event-dispatcher.eventSuffix'),
            'pathWithListeners' => GlobalConfig::get('event-dispatcher.pathWithListeners'),
            'listenerNamespace' => GlobalConfig::get('event-dispatcher.listenerNamespace'),
            'listenerSuffix'    => GlobalConfig::get('event-dispatcher.listenerSuffix')
        ];

    }

}