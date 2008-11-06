<?php

//set up some preliminary defaults, this is so all of our
//framework includes work!
if(!defined('BASE')) {
	define('BASE', realpath(dirname('../../../')));
}

//Parent of migrations directory.
if(!defined('DB_DIR')) {
	define('DB_DIR', BASE . '/tests/dummy/db');
}

//Where the dummy migrations reside
if(!defined('MIGRATION_DIR')) {
	define('MIGRATION_DIR', DB_DIR . '/migrate');
}

require_once 'Log.php'; //PEAR Log


?>