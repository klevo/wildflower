<?php 
class UtilityShell extends Shell {

    function hash() {
        $value = Configure::read('Security.salt') . trim($this->args[0]);
        $this->out(sha1($value)); // @TODO replace with Security::hash() class
    }
    
}

