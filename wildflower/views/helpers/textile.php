<?php
App::import('Vendor', 'classTextile', array('file' => 'classTextile.php'));

class TextileHelper extends AppHelper {
    
    function format($string) {
        $textile = new Textile();
        return $textile->TextileRestricted($string);
    }
    
}
