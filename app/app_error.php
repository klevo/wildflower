<?php
class AppError extends ErrorHandler {
    
    function object_not_found() {
        $this->controller->set(array('referer' => $this->controller->referer()));
        $this->__outputMessage('object_not_found');
    }
    
    function save_error() {
        $this->controller->set(array('referer' => $this->controller->referer()));
        $this->__outputMessage('save_error');
    }
    
}