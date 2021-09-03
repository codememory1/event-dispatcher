<?php

namespace Codememory\Components\Event;

use Codememory\Components\Configuration\Configuration;
use Codememory\Components\Configuration\Interfaces\ConfigInterface;
use Codememory\Components\GlobalConfig\GlobalConfig;
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
     * Utils Construct
     */
    public function __construct()
    {

        $this->config = Configuration::getInstance()->open(GlobalConfig::get('event-dispatcher.configName'), $this->defaultConfig());

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