<?php

namespace Slova\Dict;

use Symfony\Component\HttpFoundation\Response;

class IndexController
{
    public function indexAction()
    {
        $template = new MainTemplate();
        $response = new Response($template->render());

        return $response;
    }

    public function testAction($param1, $param2)
    {
        $response = new Response($param1 . ' - ' . $param2);

        return $response;
    }
}
