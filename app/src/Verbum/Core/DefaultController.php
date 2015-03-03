<?php

namespace Verbum\Core;

class DefaultController
{
    public function notFoundAction(Response $response)
    {
        $response->setCode(404);
    }

    public function exceptionAction(\Exception $exception, Response $response)
    {
        $response->setCode(500);

        // TODO: log the exception instead of showing it to user
        $response->setContent((string) $exception);
    }
}
