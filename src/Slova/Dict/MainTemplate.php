<?php

namespace Slova\Dict;

class MainTemplate
{
    protected $jsFiles = [
        'cache/javascript/deps.min.js',
        'cache/javascript/scripts.min.js',
    ];

    protected $cssFiles = [
        'cache/css/bootstrap.min.css',
        'cache/css/frontend.min.css',
    ];

    protected $template = 'templates/index.phtml';

    /**
     * @return mixed
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param mixed $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    public function getJSFilesList()
    {
        return $this->jsFiles;
    }

    public function getCSSFilesList()
    {
        return $this->cssFiles;
    }

    public function render()
    {
        global $app;
        ob_start();
        require realpath($app->config['dir']['base'] . DS . $this->getTemplate());
        return ob_get_clean();
    }
}
