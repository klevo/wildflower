<?php
/**
 * Wildflower bootstrap file
 * 
 * This file should be included in app/bootsrap.php. It connect WF
 * with your application.
 *
 * @package wildflower
 */

// Wildflower MVC paths
define('WILDFLOWER_DIR', ROOT . DS . 'wildflower');
define('SETTINGS_CACHE_FILE', TMP . 'settings' . DS . 'cache');

$modelPaths = array(WILDFLOWER_DIR . DS . 'models' . DS);
$viewPaths = array(WILDFLOWER_DIR . DS . 'views' . DS);
$controllerPaths = array(WILDFLOWER_DIR . DS . 'controllers' . DS);
$behaviorPaths = array(WILDFLOWER_DIR . DS . 'models' . DS . 'behaviors' . DS);
$helperPaths = array(WILDFLOWER_DIR . DS . 'views' . DS . 'helpers' . DS);
$componentPaths = array(WILDFLOWER_DIR . DS . 'controllers' . DS . 'components' . DS);

// Include Wildflower config
require_once(dirname(__FILE__) . DS . 'core.php');

/**
 * Dynamic class include
 *
 * @param string $className
 * @package wildflower
 */
function __autoload($className) {
	$wfClasses = array(
	   'WildflowerModel' => 'wf_model', 
	   'WildflowerController' => 'wf_controller', 
	   'WildflowerHelper' => 'wf_helper'
	);
	
	if (array_key_exists($className, $wfClasses)) {
		require_once(WILDFLOWER_DIR . DS . $wfClasses[$className] . '.php');
	}
	
	if ($className == 'ConnectionManager') {
		require_once LIBS . 'model' . DS . 'connection_manager.php';
	} else if ($className == 'DATABASE_CONFIG') {
		require_once CONFIGS . 'database.php';
	}
}

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
