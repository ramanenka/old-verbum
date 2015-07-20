<?php

namespace Verbum\Dict;

use Verbum\Core\Request;
use Verbum\Core\Response;

class ActionResultProcessor implements \Verbum\Core\ActionResultProcessor
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var MainTemplate
     */
    protected $template;

    /**
     * @param Request $request
     * @inject request
     * @return $this
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * @param Response $response
     * @inject response
     * @return $this
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;
        return $this;
    }

    /**
     * @param MainTemplate $template
     * @inject \Verbum\Dict\MainTemplate
     * @return $this
     */
    public function setTemplate(MainTemplate $template)
    {
        $this->template = $template;
        return $this;
    }

    /**
     * Setts response based on the request and action returned result
     *
     * @param $actionResult
     */
    public function process($actionResult)
    {
        $accept = $this->request->getHeader('Accept');
        if (strpos($accept, 'application/json') !== false) {
            $this->processJSON($actionResult);
        } else {
            $this->processHTML($actionResult);
        }
    }

    /**
     * Process json case
     *
     * @param $actionResult
     */
    public function processJSON($actionResult)
    {
        $this->response
            ->setContent(json_encode($actionResult))
            ->setHeader('Content-Type', 'application/json');
    }

    /**
     * Process html case
     *
     * @param $actionResult
     */
    public function processHTML($actionResult)
    {
        $this->response->setContent(
            $this->template->setData([
                'q' => $this->request->getParam('q'),
                'results' => $actionResult
            ])->render()
        )->setHeader('Content-Type', 'text/html; charset=utf-8');
    }
}
