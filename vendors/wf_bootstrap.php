<?php
/**
 * Wildflower global  functions
 * 
 * This file should be included in app/bootsrap.php.
 * PHP 5.3 requirement.
 *
 * @package wildflower
 */
date_default_timezone_set('Europe/Bratislava');

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
 * Echo with htmlspecialchars() and debug output for non-strings and non-itegers
 * 
 * @param mix $string ir integer
 * @return void
 * @package wildflower
 */
function eko($string) {
    if (!is_string($string) && !is_numeric($string)) {
        var_dump($string);
    }
    echo hsc($string);
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

/**
 * Load default Wildflower Configuration
 *
 */
App::import('Vendor', 'wf_core', array('file' => 'wf_core.php'));
