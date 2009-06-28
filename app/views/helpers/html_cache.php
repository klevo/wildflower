<?php
/*
 * HTML Cache CakePHP Helper
 * Copyright (c) 2008 Matt Curry
 * www.PseudoCoder.com
 * http://github.com/mcurry/cakephp/tree/master/helpers/html_cache
 * http://www.pseudocoder.com/archives/2008/09/03/cakephp-html-cache-helper/
 *
 * @author      mattc <matt@pseudocoder.com>
 * @modifiedBy klevo for Wildflower
 * @license     MIT
 */
class HtmlCacheHelper extends Helper {
    
    function afterLayout() {
        if (Configure::read('debug') > 0) {
            return;
        }

        $view = ClassRegistry::getObject('view');

        //handle 404s
        if ($view->name == 'CakeError') {
            $path = $this->params['url']['url'];
        } else {
            $path = substr($this->here, strlen($this->base) - strlen($this->here));
        }

        $path = implode(DS, array_filter(explode('/', $path)));
        if($path !== '') {
            $path = DS . ltrim($path, DS);
        }
        $path = WWW_ROOT . 'cache' . $path . DS . 'index.html';

        $file = new File($path, true);
        $file->write($view->output);
    }
    
}

