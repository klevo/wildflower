<?php

/*
	This is a generic task which initializes a DB for migrations.
	
	It creates a table called "schema_info" with a single column, "version"
	which specifies the current version of the schema.
	
	If the "schema_info" table already exists then this task silently
	returns and no modifications are done.
	
*/

require_once BASE . '/lib/classes/task/class.iTask.php';
require_once BASE . '/config/config.inc.php';

class DB_Setup implements iTask {
	
	private $adapter = null;
	private $create_ddl = ""; 
	
	function __construct($adapter) {
		$this->adapter = $adapter;
		
		//setup our DDL string
		$this->create_ddl =	<<<DDL
				CREATE TABLE `schema_info` (
					version int(11) unsigned not null default 0
				);
DDL;
		$this->insert_sql = "INSERT INTO schema_info (version) VALUES (0)";
	}
	
	/* Primary task entry point */
	public function execute($args) {
		echo "Started: " . date('Y-m-d g:ia T') . "\n\n";		
		echo "[db:setup]: \n";
		if( !$this->adapter->table_exists(SCHEMA_TBL_NAME) ) {
			//it doesnt exist, create it
			echo sprintf("\tCreating table: %s", SCHEMA_TBL_NAME);
			$this->adapter->execute_ddl($this->create_ddl);
			$this->adapter->execute_ddl($this->insert_sql);
			echo "\n\tDone.\n";
		} else {
			echo sprintf("\tNOTICE: %s already exists. Nothing to do.", SCHEMA_TBL_NAME);
		}
		echo "\n\nFinished: " . date('Y-m-d g:ia T') . "\n\n";		
	}
	
	
}

?>