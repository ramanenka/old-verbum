<?php

namespace Slova\Panel;

use Symfony\Component\HttpFoundation\Response;

class IndexController
{
    public function indexAction()
    {
        $response = new Response('ololo');

        return $response;
    }
}
