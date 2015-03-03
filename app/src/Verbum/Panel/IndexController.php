<?php

namespace Verbum\Panel;

use Verbum\Core\Response;

class IndexController
{
    public function indexAction(Response $r)
    {
        $r->setHeader('Content-Type', 'text/html; charset=utf-8');
        $r->setContent('ololo');
    }
}
