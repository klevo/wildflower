<?php

//set up some preliminary defaults, this is so all of our
//framework includes work!
if(!defined('RUCKUSING_BASE')) {
	define('RUCKUSING_BASE', realpath(dirname('../../../')));
}

//Parent of migrations directory.
if(!defined('RUCKUSING_DB_DIR')) {
	define('RUCKUSING_DB_DIR', RUCKUSING_BASE . '/tests/dummy/db');
}

//Where the dummy migrations reside
if(!defined('RUCKUSING_MIGRATION_DIR')) {
	define('RUCKUSING_MIGRATION_DIR', RUCKUSING_DB_DIR . '/migrate');
}

require RUCKUSING_BASE . '/lib/classes/util/class.Ruckusing_Logger.php';
require RUCKUSING_BASE . '/lib/classes/Ruckusing_exceptions.php';



?>