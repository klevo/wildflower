<?php
/**
 * Wildflower global  functions
 * 
 * This file should be included in app/bootsrap.php.
 *
 * @package wildflower
 */

/**
 * Wrapper for application encoding respecting htmlspecialchars
 * 
 * @param string $string
 * @return string Text safe to display in HTML
 * @package wildflower
 */
function hsc($string) {
	return htmlspecialchars($string, ENT_QUOTES, Configure::read('App.encoding'));
}

/**
 * FirePHP debug
 *
 * @param mixed Variables to output to FireBug console
 */
function fb() {
    if (Configure::read('debug') < 1) {
        return true;
    }
    App::import ('Vendor', 'FirePHP', array('file' => 'FirePHP.class.php'));
    $instance = FirePHP::getInstance(true);
    $args = func_get_args();
    return call_user_func_array(array($instance,'fb'),$args);
    return true;
}
