<?php 
App::import('Security');

class UtilityShell extends Shell {

    function hash() {
        $value = Configure::read('Security.salt') . trim($this->args[0]);
        $this->out(Security::hash($value, null, true));
    }
    
}

