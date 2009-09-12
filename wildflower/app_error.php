<?php
class AppError extends ErrorHandler {
    
    function object_not_found() {
        $this->controller->set(array('referer' => $this->controller->referer()));
        $this->_outputMessage('object_not_found');
    }
    
    function save_error() {
        $this->controller->set(array('referer' => $this->controller->referer()));
        $this->_outputMessage('save_error');
    }
    
    function xss() {
        $this->controller->set(array(
            'referer' => $this->controller->referer(),
            'data' => $this->controller->data,
        ));
        $this->_outputMessage('cross-site-scripting');
    }
    
}