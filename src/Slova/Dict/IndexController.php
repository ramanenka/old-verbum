<?php
/**
 * Created by PhpStorm.
 * User: vad
 * Date: 7/6/14
 * Time: 1:20 AM
 */

namespace Slova\Dict;

use Slova\Core\Response;

class IndexController {

    public function indexAction(Response $r) {
        $template = new MainTemplate();
        $r->setContent($template->render());
        $r->setHeader('Content-Type', 'text/html; charset=utf-8');
    }
} 
