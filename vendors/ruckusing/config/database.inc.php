<?php
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}
define('BASE', dirname(__FILE__) . '/..');

require_once dirname(__FILE__) . DS . '..'  
    . DS . '..'  . DS . '..'  
    . DS . 'app'  . DS . 'config' 
    . DS . 'database.php';
    
$cakeDbConfig = new DATABASE_CONFIG();

//----------------------------
// DATABASE CONFIGURATION
//----------------------------
$ruckusing_db_config = array(
	
	'development' => array(
			'type' 			=> 'mysql',
			'host' 			=> $cakeDbConfig->default['host'],
			'port'			=> 3306,
			'database' 	=> $cakeDbConfig->default['database'],
			'user' 			=> $cakeDbConfig->default['login'],
			'password' 	=> $cakeDbConfig->default['password']
	),
	'test' 					=> array(
			'type' 			=> 'mysql',
			'host' 			=> 'localhost',
			'port'			=> 3306,
			'database' 	=> 'php_migrator_test',
			'user' 			=> 'root',
			'password' 	=> ''
	),
	'production' 		=> array(
			'type' 			=> 'mysql',
			'host' 			=> 'localhost',
			'port'			=> 0,
			'database' 	=> 'prod_php_migrator',
			'user' 			=> 'root',
			'password' 	=> ''
	)
	
);
