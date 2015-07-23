<?php

namespace Verbum\Core\FrontControllerTest;

use \Verbum\Core\Response;

class ActionResultProcessor implements \Verbum\Core\ActionResultProcessor
{
    /**
     * @var Response
     */
    protected $response;

    /**
     * @param Response $response
     * @inject response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }

    public function process($actionResult)
    {
        $this->response->setHeader('Content-Type', 'application/json');
        $this->response->setContent(json_encode($actionResult));
    }
}
