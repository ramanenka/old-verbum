<?php

namespace Slova\Dict;

class MainTemplate {

    public function render() {
        ob_start();
        require BASE_PATH .'/templates/index.phtml';
        return ob_get_clean();
    }
}
