<?php

/*
	This task retrieves the current version of the schema.
*/

require_once BASE . '/lib/classes/task/class.iTask.php';
require_once BASE . '/config/config.inc.php';


class DB_Version implements iTask {
	
	private $adapter = null;
	private $create_ddl = ""; 
	
	function __construct($adapter) {
		$this->adapter = $adapter;
	}
	
	/* Primary task entry point */
	public function execute($args) {
		echo "Started: " . date('Y-m-d g:ia T') . "\n\n";		
		echo "[db:version]: \n";
		if( ! $this->adapter->table_exists(SCHEMA_TBL_NAME) ) {
			//it doesnt exist, create it
			echo "\tSchema info table does not exist. Do you need to run 'db:setup'?";
		} else {
			//it exists, read the version from it
			$version = $this->adapter->get_db()->queryOne('SELECT version FROM schema_info');
			printf("\tCurrent version: %d", $version);
		}
		echo "\n\nFinished: " . date('Y-m-d g:ia T') . "\n\n";		
	}
	
	
}

?>