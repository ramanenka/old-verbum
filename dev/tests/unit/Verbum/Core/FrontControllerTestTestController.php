<?php

namespace Verbum\Core;

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
            is_a($app, 'Verbum\Core\App') && is_a($request, 'Verbum\Core\Request')
            && is_a($response, 'Verbum\Core\Response') && is_a($controller, 'Verbum\Core\FrontController')
        ];
    }

    public function objectParamsUrlParamsAction(Request $request, $param1, $param2 = 10)
    {
        return [is_a($request, 'Verbum\Core\Request'), $param1, $param2];
    }

    public function objectFromParamsWithTypeHintAction(\Exception $exception)
    {
        return [get_class($exception)];
    }
}
