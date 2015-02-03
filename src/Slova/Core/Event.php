<?php

namespace Slova\Core;

class Event
{

    protected $data = [];

    protected $name = '';

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function get($name)
    {
        return array_key_exists($name, $this->data) ? $this->data[$name] : null;
    }

    public function setName($name)
    {
        if (!is_string($name)) {
            throw new \Exception('Only string can be used for event name. ' . gettype($name) . ' is given');
        }
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }
}
