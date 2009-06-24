<?php

/*
	This task retrieves the current version of the schema.
*/

require_once RUCKUSING_BASE . '/lib/classes/task/class.Ruckusing_iTask.php';
require_once RUCKUSING_BASE . '/config/config.inc.php';


class Ruckusing_DB_Version implements Ruckusing_iTask {
	
	private $adapter = null;
	private $create_ddl = ""; 
	
	function __construct($adapter) {
		$this->adapter = $adapter;
	}
	
	/* Primary task entry point */
	public function execute($args) {
		echo "Started: " . date('Y-m-d g:ia T') . "\n\n";		
		echo "[db:version]: \n";
		if( ! $this->adapter->table_exists(RUCKUSING_SCHEMA_TBL_NAME) ) {
			//it doesnt exist, create it
			echo "\tSchema info table does not exist. Do you need to run 'db:setup'?";
		} else {
			//it exists, read the version from it
			$version = $this->adapter->select_one('SELECT version FROM ' . $this->adapter->qualify_entity(RUCKUSING_SCHEMA_TBL_NAME));
			printf("\tCurrent version: %d", $version['version']);
		}
		echo "\n\nFinished: " . date('Y-m-d g:ia T') . "\n\n";		
	}
	
	
}

?>