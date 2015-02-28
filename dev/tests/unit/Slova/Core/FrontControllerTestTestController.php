<?php

namespace Slova\Core;

class FrontControllerTestTestController
{
    public function noParamsAction()
    {
        return ['result'];
    }

    public function urlParamsAction($param1, $param2)
    {
        return [$param1, $param2];
    }

    public function urlParamsActionWithDefault($param1, $param2 = 'value2')
    {
        return [$param1, $param2];
    }

    public function urlParamsActionNoRequired($param1, $param2)
    {
        return [$param1, $param2];
    }

    public function objectParamsAction(App $app, Request $request, Response $response, FrontController $controller)
    {
        return [
            is_a($app, 'Slova\Core\App') && is_a($request, 'Slova\Core\Request')
            && is_a($response, 'Slova\Core\Response') && is_a($controller, 'Slova\Core\FrontController')
        ];
    }

    public function objectParamsUrlParamsAction(Request $request, $param1, $param2 = 10)
    {
        return [is_a($request, 'Slova\Core\Request'), $param1, $param2];
    }

    public function objectFromParamsWithTypeHintAction(\Exception $exception)
    {
        return [get_class($exception)];
    }
}
