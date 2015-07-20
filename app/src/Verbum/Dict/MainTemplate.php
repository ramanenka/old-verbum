<?php

namespace Verbum\Dict;

class MainTemplate
{
    protected $jsFiles = [
        'cache/javascript/scripts.min.js',
    ];

    protected $cssFiles = [
        'cache/css/frontend.min.css',
    ];

    protected $template = 'app/templates/index.phtml';

    /**
     * Template data
     *
     * @var array
     */
    protected $data = array();

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

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }


    /**
     * Returns array of javascript files to load on the main page
     *
     * @return array
     */
    public function getJSFilesList()
    {
        return array_map([$this, 'addVersionPostfix'], $this->jsFiles);
    }

    /**
     * Returns array of css files to load on the main page
     *
     * @return array
     */
    public function getCSSFilesList()
    {
        return array_map([$this, 'addVersionPostfix'], $this->cssFiles);
    }

    /**
     * Adds version to the specified file to be able to boost all statics
     * when it has changed
     *
     * @param $file
     * @return string
     */
    protected function addVersionPostfix($file)
    {
        return $file . '?v=' . $this->fileMTime('public/' . $file);
    }

    /**
     * Returns file's modified time if file exists
     *
     * @param $file
     * @return bool|int
     */
    protected function fileMTime($file)
    {
        return file_exists($file) ? filemtime($file) : false;
    }

    public function render()
    {
        global $app;
        ob_start();
        require realpath($app->config['dir']['base'] . DS . $this->getTemplate());
        return ob_get_clean();
    }
}
