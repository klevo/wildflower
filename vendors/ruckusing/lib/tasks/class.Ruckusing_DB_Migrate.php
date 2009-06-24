<?php

/*
	This is the primary work-horse method, it runs all migrations available,
	up to the current version.
*/

require_once RUCKUSING_BASE . '/lib/classes/task/class.Ruckusing_iTask.php';
require_once RUCKUSING_BASE . '/config/config.inc.php';
require_once RUCKUSING_BASE . '/lib/classes/Ruckusing_exceptions.php';
require_once RUCKUSING_BASE . '/lib/classes/util/class.Ruckusing_MigratorUtil.php';
require_once RUCKUSING_BASE . '/lib/classes/util/class.Ruckusing_VersionUtil.php';
require_once RUCKUSING_BASE . '/lib/classes/class.Ruckusing_BaseMigration.php';


class Ruckusing_DB_Migrate implements Ruckusing_iTask {
	
	private $adapter = null;
	private $task_args = array(); 
	private $regexp = '/^(\d+)\_/';
	
	function __construct($adapter) {
		$this->adapter = $adapter;
	}
	
	/* Primary task entry point */
	public function execute($args) {
	  $output = "";
	  if(!$this->adapter->supports_migrations()) {
	   die("This database does not support migrations.");
    }
		$this->task_args = $args;
		echo "Started: " . date('Y-m-d g:ia T') . "\n\n";		
		echo "[db:migrate]: \n";
		try {
  	  // Check that the schema_info table exists, and if not, automatically create it
  	  $this->verify_environment();

			$goto_version = 1;
			//$this->extract_args($args);
			$db_version = $this->get_db_version();
			//did the user specify a version?
			if(array_key_exists('VERSION', $this->task_args)) {
				$goto_version = (int)$this->task_args['VERSION'];
			} else {
				//get the highest file version	
				$goto_version = Ruckusing_VersionUtil::get_highest_migration(RUCKUSING_MIGRATION_DIR);
			}
			if($db_version == $goto_version) {
			 //nothing to do
			 echo "\tNo migrations to run. DB is already at the current version ($db_version).\n"; 
		  } else if($db_version > $goto_version) {
				//GOING DOWN
				$output = $this->migrate_down($goto_version, $db_version);
			} else if($db_version < $goto_version) {
				//GOING UP
				$output = $this->migrate_up($db_version, $goto_version);
			}
			if(!empty($output)) {
			  echo $output . "\n\n";
		  }
		}catch(Ruckusing_MissingSchemaInfoTableException $ex) {
			echo "\tSchema info table does not exist. I tried creating it but failed. Check permissions.";
		}catch(Ruckusing_MissingMigrationDirException $ex) {
			echo "\tMigration directory does not exist: " . RUCKUSING_MIGRATION_DIR;
		}catch(Ruckusing_Exception $ex) {
			die("\n\n" . $ex->getMessage() . "\n\n");
		}	
		echo "\n\nFinished: " . date('Y-m-d g:ia T') . "\n\n";			
	}
	
	private function get_db_version() {
		if($this->adapter->table_exists(RUCKUSING_SCHEMA_TBL_NAME) ) {
			//return 
			$version = $this->adapter->select_one(sprintf('SELECT version FROM %s', $this->adapter->qualify_entity(RUCKUSING_SCHEMA_TBL_NAME)));			
			return (int)$version['version'];
		} else {
			throw new Ruckusing_MissingSchemaInfoTableException();
		}
	}//get_db_version

	private function migrate_up($current, $destination) {
		try {
			echo "\tMigrating UP to: {$destination} (current version: {$current})\n";		
			$migrations = Ruckusing_MigratorUtil::migration_files(RUCKUSING_MIGRATION_DIR, 'up', $current, $destination);
			if(count($migrations) == 0) {
				return "\nNo relevant migrations to run. Exiting...\n";
			}
			$result = $this->run_migrations($migrations, 'up', $destination);
		}catch(Exception $ex) {
			throw $ex;
		}		
	}//migrate_up

	private function migrate_down($destination, $current) {
		try {
			echo "\tMigrating DOWN to: {$destination} (current version: {$current})\n";		
			$migrations = Ruckusing_MigratorUtil::migration_files(RUCKUSING_MIGRATION_DIR, 'down', $current, $destination);
			$result = $this->run_migrations($migrations, 'down', $destination);
			if(count($migrations) == 0) {
				return "\nNo relevant migrations to run. Exiting...\n";
			}
		}catch(Exception $ex) {
			throw $ex;
		}		
	}//migrate_down
	
	private function run_migrations($migrations, $target_method, $destination) {
		$last_version = -1;
		foreach($migrations as $file) {
			$full_path = RUCKUSING_MIGRATION_DIR . '/' . $file['file'];
			if(is_file($full_path) && is_readable($full_path) ) {
				require_once $full_path;
				$klass = Ruckusing_NamingUtil::class_from_migration_file($file['file']);
				$obj = new $klass();
				$refl = new ReflectionObject($obj);
				if($refl->hasMethod($target_method)) {
					$obj->set_adapter($this->adapter);
					$start = $this->start_timer();
					try {
						//start transaction
						$this->adapter->start_transaction();
						$result =  $obj->$target_method();
						//successfully ran migration, update our version and commit
						$version = $this->resolve_versions($destination, $file['version'], $target_method);						
						$this->adapter->set_current_version($version);										
						$this->adapter->commit_transaction();
					}catch(Exception $e) {
						$this->adapter->rollback_transaction();
						//wrap the caught exception in our own
						$ex = new Exception(sprintf("%s - %s", $file['class'], $e->getMessage()));
						throw $ex;
					}
					$end = $this->end_timer();
					$diff = $this->diff_timer($start, $end);
					printf("========= %s ======== (%.2f)\n", $file['class'], $diff);
					$last_version = $file['version'];
					$exec = true;
				} else {
					trigger_error("ERROR: {$klass} does not have a '{$target_method}' method defined!");
				}			
			}//is_file			
		}//foreach
		//update the schema info
		$result = array('last_version' => $last_version);
		return $result;
	}//run_migrations
	
	private function start_timer() {
		return microtime(true);
	}

	private function end_timer() {
		return microtime(true);
	}
	
	private function diff_timer($s, $e) {
		return $e - $s;
	}
	
	private function resolve_versions($destination, $last_exec_version, $direction) {
		if($direction != 'up' && $direction != 'down') {
			throw new Exception("Invalid direction parameter: {$direction}");
		}
		if($direction == 'down') {
			if($destination == ($last_exec_version - 1)) {
				return $destination;
			} else {
				return $last_exec_version;
			}
		}
		if($direction == 'up') {
			return $last_exec_version;
		}
	}//resolve_version
	
	private function verify_environment() {
	  if(!$this->adapter->table_exists(RUCKUSING_SCHEMA_TBL_NAME) ) {
			echo "\n\tSchema info table does not exist. Auto-creating.";
	    $this->auto_create_schema_info_table();
    }	 
  }
	
	private function auto_create_schema_info_table() {
	  try {
  		echo sprintf("\n\tCreating table: %s", RUCKUSING_SCHEMA_TBL_NAME . "\n\n");
  		$table=$this->adapter->create_table(RUCKUSING_SCHEMA_TBL_NAME, array('id' => false));
  		$table->column('version', 'integer', array('default' => 0, 'null' => false));
  		$table->finish();
  		$this->adapter->execute_ddl(sprintf('INSERT INTO %s (version) VALUES (0)', $this->adapter->qualify_entity(RUCKUSING_SCHEMA_TBL_NAME)));
  		return true;
		}catch(Exception $e) {
		  die("\nError auto-creating '" . RUCKUSING_SCHEMA_TBL_NAME . "' table: " . $e->getMessage() . "\n\n");
	  }
	}
	
	
}//class

?>