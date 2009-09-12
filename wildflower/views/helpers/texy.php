<?php
App::import('Vendor', 'Texy', array('file' => 'texy/texy.php'));

class TexyHelper extends Helper {
    
    function process($text) {
        $texy = new Texy();
        return $texy->process($text); 
    }
    
}