# Event Dispatcher

## Установка

```
composer require codememory/event-dispatcher
```

## Обзор конфигурации
```yaml
# configs/event.yaml

event:
  # Settings events
  pathWithEvents: App/Events       # Path with all events
  eventNamespace: App\Events       # Namespace events
  eventSuffix: Event               # Suffix for filename event
  
  # Settings listeners
  pathWithListeners: App/Listeners # Path with all event listeners
  listenerNamespace: App\Listeners # Namespace event listeners
  listenerSuffix: Listener         # Suffix for filename listener
```

> Если используется конфигурация, выполните следующие команды
* Создать глобальную конфигурацию, если ее не существует
    * `php vendor/bin/gc-cdm g-config:init`
* Merge всей конфигурации
    * `php vendr/bin/gc-cdm g-config:merge --all`

## Примеры использования
### Класс события
```php
<?php

namespace App\Events;

use Codememory\Components\Event\Interfaces\EventInterface;

class OutputTextEvent implements EventInterface
{

    /**
     * @inheritDoc
     */
    public function getListeners() : array
    {
        
        return [];
        
    }

}
```
### Добавление события и слушателя
```php
<?php

use Codememory\Components\Event\EventDispatcher;
use App\Events\OutputTextEvent;

require_once 'vendor/autoload.php';

$eventDispatcher = new EventDispatcher();

$eventDispatcher->addEvent(OutputTextEvent::class)
    ->addListener(function (OutputTextEvent $event) {
        echo 123;
    });
```

### Выброс событий
```php
use Codememory\Components\Event\Dispatcher;

$dispatcher = new Dispatcher();

$dispatcher->dispatch(OutputTextEvent::class);

// 123
```

## Event(EventBuilderInterface) методы
* `setAlias(): EventBuilderInterface` Добавить alias к событию
    * string **$alias**


* `addListener(): EventBuilderInterface` Добавить слушателя к событию
    * string|callable **$listener** - namespace или callback слушателя

    
* `setParameters(): EventBuilderInterface` Добавить параметры, которые будут переданы в конструктор события
    * array **$parameters**

## Event(EventDataInterface) методы
* `getNamespace(): string` Возвращает namespace события


* `getReflector(): ReflectionClass` Возвращает рефлектор события


* `getListeners(): array` Возвращает массив слушателей события


* `getParameters(): array` Возвращает параметры для события

## EventDispatcher методы
* `addEvent(): EventBuilderInterface` Добавить новое событие


* `addEventListener(): EventDispatcherInterface` Добавить слушателя для события
    * string **$eventName** - namespace или alias события
    * string|callable **$listener**


* `existEvent(): bool` Проверить существование события
    * string **$eventName** - namespace или alias события


* `getEvent(): EventInterface` Получить событие
    * string **$eventName** - namespace или alias события


* `getEventListeners(): array` Возвращает массив слушателей события
    * string **$eventName** - namespace или alias события