<?php
/**
 * An example of extending Wildflower plugin controller
 *
 */
class WildflowerAppControllerCallback extends WildflowerCallback {
    
    function beforeFilter() {
        echo "This is a Wildflower callback!";
    }
    
}