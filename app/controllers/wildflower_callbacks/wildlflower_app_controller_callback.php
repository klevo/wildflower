<?php
/**
 * An example of extending Wildflower plugin controller
 *
 */
class AppControllerCallback extends WildflowerCallback {
    
    function beforeFilter() {
        echo "This is a Wildflower callback!";
    }
    
}