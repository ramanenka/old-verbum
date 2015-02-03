<?php

namespace Slova\Core\Event;

interface ManagerInterface
{

    public function dispatch($event, array $params = []);
}
