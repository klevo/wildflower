<?php

/*
	This is a generic task which dumps the schema of the DB
	as a text file.	
*/

require_once BASE . '/lib/classes/task/class.iTask.php';
require_once BASE . '/config/config.inc.php';

class DB_Schema implements iTask {
	
	private $adapter = null;
	
	function __construct($adapter) {
		$this->adapter = $adapter;
	}
	
	/* Primary task entry point */
	public function execute($args) {
		try {
			echo "Started: " . date('Y-m-d g:ia T') . "\n\n";		
			echo "[db:schema]: \n";
			$schema = $this->adapter->schema();
			if($schema != "") {
				//write to disk
				$schema_file = DB_DIR . '/schema.txt';
				file_put_contents($schema_file, $schema, LOCK_EX);
				echo "\tSchema written to: $schema_file\n\n";
			}
			echo "\n\nFinished: " . date('Y-m-d g:ia T') . "\n\n";							
		}catch(Exception $ex) {
			throw $ex; //re-throw
		}
	}//execute
	
}//class

?>