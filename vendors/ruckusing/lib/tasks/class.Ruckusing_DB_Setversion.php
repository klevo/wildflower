<?php

/*

  This task can set the version stored in the 'schema_info' table. Useful if 
  You want to start from a version other than 0.

  k at isr.hu
	
*/

require_once RUCKUSING_BASE . '/lib/classes/task/class.Ruckusing_iTask.php';
require_once RUCKUSING_BASE . '/config/config.inc.php';

class Ruckusing_DB_Setversion implements Ruckusing_iTask {
	
	private $adapter = null;
	
	function __construct($adapter) {
		$this->adapter = $adapter;
	}
	
	/* Primary task entry point */
	public function execute($args) {
		echo "Started: " . date('Y-m-d g:ia T') . "\n\n";		
		echo "[db:setversion]: \n";
		if( ! $this->adapter->table_exists(RUCKUSING_SCHEMA_TBL_NAME) ) {
			// schema_info table does not exist
			echo "\tSchema info table does not exist. Do you need to run 'db:setup'?";
    } elseif( ! isset($args['VERSION'])) {
      // no VERSION is set
			echo "\tYou have to provide a VERSION to be set. Example: db:setversion VERSION=7.";
    } elseif(is_numeric($args['VERSION'])) {
      $this->adapter->set_current_version($args['VERSION']);
      echo "\tVersion updated to ".$args['VERSION'];
    }
		echo "\n\nFinished: " . date('Y-m-d g:ia T') . "\n\n";		
	}
	
}

?>
