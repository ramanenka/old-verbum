<?php

namespace Slova\Core\Event;

use Slova\Core\Event;

class Manager implements ManagerInterface
{

    protected $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    protected $events = [];

    public function addObserver($name, $config)
    {
        if (!isset($this->events[$name])) {
            $this->events[$name] = [];
        }
        $this->events[$name][] = $config;
    }

    public function dispatch($eventName, array $params = [])
    {
        if (isset($this->events[$eventName])) {
            $event = new Event($params);
            $event->setName($eventName);
            foreach ($this->events[$eventName] as $key => $observer) {
                if (isset($observer['instance'])) {
                    $instance = $observer['instance'];
                } elseif (isset($observer['di_alias'])) {
                    $instance = $this->app->getDi()->get($observer['di_alias']);
                    $this->events[$eventName]['instance'] = $instance;
                }
                $instance->$observer['method']($event);
            }
        }
    }
}
