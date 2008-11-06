<?php

//--------------------------------------------
//Overall file system configuration paths
//--------------------------------------------

//These might already be defined, so wrap them in checks


// DB table where the version info is stored
if(!defined('SCHEMA_TBL_NAME')) {
	define('SCHEMA_TBL_NAME', 'schema_info');
}

//Parent of migrations directory.
//Where schema.txt will be placed when 'db:schema' is executed
if(!defined('DB_DIR')) {
	define('DB_DIR', BASE . '/db');
}

//Where the actual migrations reside
if(!defined('MIGRATION_DIR')) {
	define('MIGRATION_DIR', DB_DIR . '/migrate');
}

?>