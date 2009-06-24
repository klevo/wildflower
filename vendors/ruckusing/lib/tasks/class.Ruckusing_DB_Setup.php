<?php

/*
	This is a generic task which initializes a DB for migrations.
	
	It creates a table called "schema_info" with a single column, "version"
	which specifies the current version of the schema.
	
	If the "schema_info" table already exists then this task silently
	returns and no modifications are done.
	
*/

require_once RUCKUSING_BASE . '/lib/classes/task/class.Ruckusing_iTask.php';
require_once RUCKUSING_BASE . '/config/config.inc.php';

class Ruckusing_DB_Setup implements Ruckusing_iTask {
	
	private $adapter = null;
	
	function __construct($adapter) {
		$this->adapter = $adapter;
		$this->insert_sql = "INSERT INTO " . $this->adapter->qualify_entity(RUCKUSING_SCHEMA_TBL_NAME) . " (version) VALUES (0)";
	}
	
	/* Primary task entry point */
	public function execute($args) {
		echo "Started: " . date('Y-m-d g:ia T') . "\n\n";		
		echo "[db:setup]: \n";
		if( !$this->adapter->table_exists(RUCKUSING_SCHEMA_TBL_NAME) ) {
			//it doesnt exist, create it
			echo sprintf("\tCreating table: %s", RUCKUSING_SCHEMA_TBL_NAME);
			$table=$this->adapter->create_table(RUCKUSING_SCHEMA_TBL_NAME, array('id' => false));
			$table->column('version', 'integer', array('default' => 0, 'null' => false));
			$table->finish();
			$this->adapter->execute_ddl($this->insert_sql);
			echo "\n\tDone.\n";
		} else {
			echo sprintf("\tNOTICE: %s already exists. Nothing to do.", RUCKUSING_SCHEMA_TBL_NAME);
		}
		echo "\n\nFinished: " . date('Y-m-d g:ia T') . "\n\n";		
	}
	
	
}

?>