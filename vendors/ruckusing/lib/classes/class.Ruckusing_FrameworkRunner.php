<?php

//requirements
require_once RUCKUSING_BASE . '/lib/classes/task/class.Ruckusing_TaskManager.php';

/*
	Primary work-horse class. This class bootstraps the framework by loading
	all adapters and tasks.
*/

class Ruckusing_FrameworkRunner {
	
	private $db = null; //reference to our DB connection
	private $active_db_config; //the currently active config 
	private $db_config = array(); //all available DB configs (e.g. test,development, production)
	private $task_mgr = null;
	private $adapter = null;
	private $cur_task_name = "";
	private $task_options = "";
	private $ENV = "development"; //default (can also be one 'test', 'production')
	
	//set up some defaults
	private $opt_map = array(
		'ENV'					=> 'development'
	);
	
	function __construct($db, $argv) {
		try {
			set_error_handler( array("Ruckusing_FrameworkRunner", "scr_error_handler"), E_ALL );

			//parse arguments
			$this->parse_args($argv);

			//initialize logger
			$log_dir = RUCKUSING_BASE . "/logs";
			if(is_dir($log_dir) && !is_writable($log_dir)) {
				die("\n\nCannot write to log directory: $log_dir\n\nCheck permissions.\n\n");
			}elseif(!is_dir($log_dir)){
				//try and create the log directory
				mkdir($log_dir);
			}
			$log_name = sprintf("%s.log", $this->ENV);
			$this->logger = Ruckusing_Logger::instance($log_dir . "/" . $log_name);
			
			//include all adapters
			$this->load_all_adapters(RUCKUSING_BASE . '/lib/classes/adapters');
			$this->db_config = $db;
			$this->initialize_db();
			$this->init_tasks();
		}catch(Exception $e) {
		}
	}//constructor
	
	function __destruct() {
	}
	
	//-------------------------
	// PUBLIC METHODS
	//-------------------------	
	public function execute() {
		if($this->task_mgr->has_task($this->cur_task_name)) {
			$output = $this->task_mgr->execute($this->cur_task_name, $this->task_options);
			$this->display_results($output);
		} else {
			trigger_error(sprintf("Task not found: %s", $this->cur_task_name));
		}
		if($this->logger) {
		  $this->logger->close();
	  }
	}
	
	public function init_tasks() {
		$this->task_mgr = new Ruckusing_TaskManager($this->adapter);
	}
	
	public function initialize_db() {
		try {
			$this->verify_db_config();			
			$db = $this->db_config[$this->ENV];
			$adapter = $this->get_adapter_class($db['type']);
			
			if($adapter === null) {
				trigger_error(sprintf("No adapter available for DB type: %s", $db['type']));
				exit;
			}			
			//construct our adapter			
			$this->adapter = new $adapter($db, $this->logger);
		}catch(Exception $ex) {
			trigger_error(sprintf("\n%s\n",$ex->getMessage()));
		}
	}
	
	/*
		$argv is our complete command line argument set.
		PHP gives us: 
		[0] = the actual file name we're executing
		[1..N] = all other arguments
		
		Our task name should be at slot [1] 
		Anything else are additional parameters that we can pass
		to our task and they can deal with them as they see fit.
	*/
	private function parse_args($argv) {
		
		$num_args = count($argv);

		if($num_args >= 2) {					
			$this->cur_task_name = $argv[1];			
			$options = array();
			for($i = 2; $i < $num_args;$i++) {
				$arg = $argv[$i];
				if(strpos($arg, '=') !== FALSE) {
					list($key, $value) = split("=", $arg);
					$options[$key] = $value;
					if($key == 'ENV') {
						$this->ENV = $value;
					}
				}
			}
			$this->task_options = $options;
		}		
	}//parse_args()

	/*
		Global error handler to process all errors
		during script execution
	*/
	public static function scr_error_handler($errno, $errstr, $errfile, $errline) {
		die(sprintf("\n\n(%s:%d) %s\n\n", basename($errfile), $errline, $errstr));
	}

	//-------------------------
	// PRIVATE METHODS
	//-------------------------	
	private function display_results($output) {
		return;
		//deprecated
		echo "\nStarted: " . date('Y-m-d g:ia T') . "\n\n";		
		echo "\n\n";
		echo "\n$output\n";		
		echo "\nFinished: " . date('Y-m-d g:ia T') . "\n\n";		
	}
	
	private function set_opt($key, $value) {
		if(!$key) { return; }		
		$this->opt_map[$key] = $value;		
	}
	
	private function verify_db_config() {
		if( !array_key_exists($this->ENV, $this->db_config)) {
			throw new Exception(sprintf("Error: '%s' DB is not configured",$this->ENV));
		}
		$this->active_db_config = $this->db_config[$this->ENV];
		if(!array_key_exists("type",$this->active_db_config)) {
			throw new Exception(sprintf("Error: 'type' is not set for '%s' DB",$this->ENV));			
		}
		if(!array_key_exists("host",$this->active_db_config)) {
			throw new Exception(sprintf("Error: 'host' is not set for '%s' DB",$this->ENV));			
		}
		if(!array_key_exists("database",$this->active_db_config)) {
			throw new Exception(sprintf("Error: 'database' is not set for '%s' DB",$this->ENV));			
		}
		if(!array_key_exists("user",$this->active_db_config)) {
			throw new Exception(sprintf("Error: 'user' is not set for '%s' DB",$this->ENV));			
		}
		if(!array_key_exists("password",$this->active_db_config)) {
			throw new Exception(sprintf("Error: 'password' is not set for '%s' DB",$this->ENV));			
		}
	}//verify_db_config

	private function get_adapter_class($db_type) {
		$adapter_class = null;
		switch($db_type) {
			case 'mysql':
				$adapter_class = "Ruckusing_MySQLAdapter";
				break;
			case 'mssql':
				$adapter_class = "Ruckusing_MSSQLAdapter";
				break;
			case 'pgsql':
				$adapter_class = "Ruckusing_PostgresAdapter";
				break;
		}
		return $adapter_class;
	}
	
	
	/*
		DB adapters are classes in lib/classes/adapters
		and they follow the file name syntax of "class.<DB Name>Adapter.php".
		
		See the function "get_adapter_class" in this class for examples.
	*/
	private function load_all_adapters($adapter_dir) {
		if(!is_dir($adapter_dir)) {
			trigger_error(sprintf("Adapter dir: %s does not exist", $adapter_dir));
			return false;
		}
		$files = scandir($adapter_dir);
		$regex = '/^class\.(\w+)Adapter\.php$/';
		foreach($files as $f) {			
			//skip over invalid files
			if($f == '.' || $f == ".." || !preg_match($regex,$f) ) { continue; }
			require_once $adapter_dir . '/' . $f;
		}
	}
	
}//class

?>
