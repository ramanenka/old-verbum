<?php

namespace Slova\Dict;

class MainTemplate {

    protected $jsFiles = [
        'vendor/jquery/dist/jquery.js',
        'vendor/underscore/underscore.js',
        'vendor/backbone/backbone.js',
        'vendor/backbone.babysitter/lib/backbone.babysitter.js',
        'vendor/backbone.wreqr/lib/backbone.wreqr.js',
        'vendor/marionette/lib/backbone.marionette.js',
    ];

    protected $template = 'templates/index.phtml';

    /**
     * @return mixed
     */
    public function getTemplate() {
        return $this->template;
    }

    /**
     * @param mixed $template
     */
    public function setTemplate($template) {
        $this->template = $template;
    }

    public function getJSFilesList() {
        return $this->jsFiles;
    }

    public function render() {
        ob_start();
        require realpath(BASE_PATH . DS . $this->getTemplate());
        return ob_get_clean();
    }
}
