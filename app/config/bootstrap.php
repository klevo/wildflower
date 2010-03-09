<?php
/* SVN FILE: $Id: bootstrap.php 7945 2008-12-19 02:16:01Z gwoo $ */
/**
 * Short description for file.
 *
 * Long description for file
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework (http://www.cakephp.org)
 * Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 * @link          http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app.config
 * @since         CakePHP(tm) v 0.10.8.2117
 * @version       $Revision: 7945 $
 * @modifiedby    $LastChangedBy: gwoo $
 * @lastmodified  $Date: 2008-12-18 20:16:01 -0600 (Thu, 18 Dec 2008) $
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
/**
 *
 * This file is loaded automatically by the app/webroot/index.php file after the core bootstrap.php is loaded
 * This is an application wide file to load any function that is not used within a class define.
 * You can also use this to include or require any files in your application.
 *
 */

/**
 * @todo find the 1.3 way set achieve this validation
 */
/**
 * Not empty.
 */
	define('VALID_NOT_EMPTY', '/.+/');
/**
 * Numbers [0-9] only.
 */
	define('VALID_NUMBER', '/^[-+]?\\b[0-9]*\\.?[0-9]+\\b$/');
/**
 * A valid email address.
 */
	define('VALID_EMAIL', "/^[a-z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+(?:[a-z]{2,4}|museum|travel)$/i");
/**
 * A valid year (1000-2999).
 */
	define('VALID_YEAR', '/^[12][0-9]{3}$/');

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
 
// Wildflower include core settings - bootstrap may not be needed in cake1.3
App::import('Vendor', 'wf_bootsrap', array('file' => 'wf_bootstrap.php'));
App::import('Vendor', 'wf_core', array('file' => 'wf_core.php'));

/**
 * Wildflower Additional Class Paths
 *
 * convert to App::build(array());
 */

define('WILDFLOWER_DIR', APP . '..' . DS . 'wildflower' . DS);
App::build(array(
	'plugins' => array(
		WILDFLOWER_DIR . 'plugins' . DS,
		APP . 'plugins' . DS
		),
	'models' =>  array(
		WILDFLOWER_DIR . 'models' . DS,
		APP . 'models' . DS
		),
	'views' => array(
		WILDFLOWER_DIR . 'views' . DS,
		APP . 'views' . DS
		),
	'controllers' => array(
		WILDFLOWER_DIR . 'controllers' . DS, 
		APP . 'controllers' . DS
		),
	'datasources' => array(
		WILDFLOWER_DIR . 'models' . DS . 'datasources' . DS, 
		APP . 'models' . DS . 'datasources' . DS
		),
	'behaviors' => array(
		WILDFLOWER_DIR . 'models' . DS . 'behaviors' . DS, 
		APP . 'models' . DS . 'behaviors' . DS
		),
	'components' => array(
		WILDFLOWER_DIR . 'controllers' . DS . 'components' . DS,
		APP . 'controllers' . DS . 'components' . DS
		),
	'helpers' => array(
		WILDFLOWER_DIR . 'views' . DS . 'helpers' . DS, 
		APP . DS . 'views' . DS . 'helpers' . DS
		),
	'vendors' => array(
		WILDFLOWER_DIR . 'vendors' . DS, 
		APP . 'vendors' . DS
		),
	'shells' => array(
		WILDFLOWER_DIR . DS . 'vendors' . DS . 'shells' . DS,
		APP . 'vendors' . DS . 'shells' . DS
		),
	'locales' => array(
		WILDFLOWER_DIR . 'locales' . DS,
		APP . 'locales' . DS
		)
	)
);