<?php

require_once BASE . '/lib/classes/class.BaseAdapter.php';
require_once BASE . '/lib/classes/class.iAdapter.php';
require_once BASE . '/lib/classes/adapters/class.MySQLTableDefinition.php';
require_once BASE . '/lib/classes/util/class.NamingUtil.php';	

define('SELECT', 2);
define('INSERT', 4);
define('UPDATE', 8);
define('DELETE', 16);
define('ALTER', 32);
define('DROP', 64);
define('CREATE', 128);



class MySQLAdapter extends BaseAdapter implements iAdapter {

	private $name = "MySQL";
	private $tables = array();
	private $tables_loaded = false;
	private $version = '1.0';
	private $in_trx = false;

	function __construct($dsn, $logger) {
		parent::__construct($dsn);
		$this->connect($dsn);
		$this->set_logger($logger);

		//Native MDB2 functionality. Needed for listTables() functionality		
		$this->get_db()->loadModule('Manager');				
	}
	
	public function supports_migrations() {
	 return true;
  }
	
	public function native_database_types() {
		$types = array(
      'primary_key' => "int(11) UNSIGNED auto_increment PRIMARY KEY",
      'string'      => array('name' => "varchar", 	'limit' 		=> 255, 'mdb_type' 	=> 'text'),
      'text'        => array('name' => "text", 													'mdb_type' 	=> 'text'),
      'integer'     => array('name' => "int", 			'limit' 		=> 11, 	'mdb_type' 	=> 'integer'),
      'float'       => array('name' => "float", 												'mdb_type' 	=> 'float'),
      'decimal'     => array('name' => "decimal", 											'mdb_type' 	=> 'decimal'),
      'datetime'    => array('name' => "datetime", 											'mdb_type' 	=> 'timestamp'),
      'timestamp'   => array('name' => "datetime", 											'mdb_type' 	=> 'timestamp'),
      'time'        => array('name' => "time", 													'mdb_type' 	=> 'timestamp'),
      'date'        => array('name' => "date", 													'mdb_type' 	=> 'date'),
      'binary'      => array('name' => "blob", 													'mdb_type' 	=> 'blob'),
      'boolean'     => array('name' => "tinyint", 	'limit' 		=> 1, 	'mdb_type' 	=> 'integer')
			);
		return $types;
	}
	
	//-----------------------------------
	// PUBLIC METHODS
	//-----------------------------------
	
	//transaction methods
	public function start_transaction() {
		try {
			if($this->get_db()->inTransaction() == false) {
				$this->get_db()->beginTransaction();
			}
		}catch(Exception $e) {
			trigger_error($e->getMessage());
		}
	}
	public function commit_transaction() {
		try {
			if($this->get_db()->inTransaction()) {
				$this->get_db()->commit();
			}
		}catch(Exception $e) {
			trigger_error($e->getMessage());
		}
	}
	public function rollback_transaction() {
		try {
			if($this->get_db()->inTransaction()) {
				$this->get_db()->rollback();
			}
		}catch(Exception $e) {
			trigger_error($e->getMessage());
		}
	}
	

	
	public function column_definition($column_name, $type, $options = null) {
		$col = new ColumnDefinition($this, $column_name, $type, $options);
		return $col->__toString();
	}//column_definition

	//-------- DATABASE LEVEL OPERATIONS
	public function database_exists($db) {
		$ddl = sprintf("SHOW CREATE DATABASE `%s`", $db);
		$this->logger->log($ddl);
		$result = $this->get_db()->exec($ddl);
		if($this->isError($result)) {
			//trigger_error($result->getMessage());
			return false;
		}
		if( (int)$result == 1) {
			return true;
		} else {
			return false;
		}		
	}
	public function create_database($db) {
		if($this->database_exists($db)) {
			//trigger_error("ERROR: the database '{$db}' already exists. Cannot create a new one.");			
			return false;
		}
		$ddl = sprintf("CREATE DATABASE `%s`", $db);
		$this->logger->log($ddl);
		$result = $this->get_db()->exec($ddl);
		if($this->isError($result)) {
			//trigger_error($result->getMessage());
			return false;
		}
		if( (int)$result == 1) {
			return true;
		} else {
			return false;
		}		
	}
	
	public function drop_database($db) {
		if(!$this->database_exists($db)) {
			//trigger_error("ERROR: the database '{$db}' does not exist. Cannot drop an non-existent DB!");
			return false;
		}
		$ddl = sprintf("DROP DATABASE IF EXISTS `%s`", $db);
		$this->logger->log($ddl);
		$result = $this->get_db()->exec($ddl);
		if($this->isError($result)) {
			//trigger_error($result->getMessage());
			return false;
		}
		if( (int)$result == 0) {
			return true;
		} else {
			return false;
		}		
	}

	/*
		Dump the complete schema of the DB. This is really just all of the 
		CREATE TABLE statements for all of the tables in the DB.
		
		NOTE: this does NOT include any INSERT statements or the actual data
		(that is, this method is NOT a replacement for mysqldump)
	*/
	public function schema() {
		$final = "";
		$this->load_tables(true);
		foreach($this->tables as $tbl => $idx) {

			if($tbl == 'schema_info') { continue; }

			$stmt = "SHOW CREATE TABLE `$tbl`";
			//echo $stmt;
			$result = $this->get_db()->queryRow($stmt);

			if($this->isError($result)) {
				trigger_error($result->getMessage());
			}
			
			if(is_array($result) && count($result) == 2) {
				$final .= $result[1] . ";\n\n";
			}
		}
		return $final;
	}
	
	public function table_exists($tbl, $reload_tables = false) {
		$this->load_tables($reload_tables);
		return array_key_exists($tbl, $this->tables);
	}
		
	public function show_fields_from($tbl) {
		return "";
	}

	public function execute($query) {
		return $this->query($query);
	}

	public function query($query) {
		$this->logger->log($query);
		$query_type = $this->determine_query_type($query);
		if($query_type == SELECT) {
			$result = $this->get_db()->queryAll($query,null, MDB2_FETCHMODE_ASSOC);
		} else {
			$result = $this->get_db()->exec($query);
		}
		if($this->isError($result)) {
			trigger_error(sprintf("Error executing 'query' with:\n%s\n\nReason: %s", $query, $result->getMessage()));
		}
		return $result;
	}
	
	public function select_one($query) {
		$this->logger->log($query);
		$query_type = $this->determine_query_type($query);
		if($query_type == SELECT) {
			$result = $this->get_db()->queryRow($query,null, MDB2_FETCHMODE_ASSOC);
		}
		if($this->isError($result)) {
			trigger_error(sprintf("Error executing 'query' with:\n%s\n\nReason: %s", $query, $result->getMessage()));
		}
		return $result;		
	}

	public function select_all($query) {
		$this->logger->log($query);
		$query_type = $this->determine_query_type($query);
		if($query_type == SELECT) {
			$result = $this->get_db()->queryAll($query,null, MDB2_FETCHMODE_ASSOC);
		}
		if($this->isError($result)) {
			trigger_error(sprintf("Error executing 'query' with:\n%s\n\nReason: %s", $query, $result->getMessage()));
		}
		return $result;		
	}
	

	/*
		Use this method for non-SELECT queries
		Or anything where you dont necessarily expect a result string, e.g. DROPs, CREATEs, etc.
	*/
	public function execute_ddl($ddl) {
		$this->logger->log($ddl);
		$result = $this->get_db()->exec($ddl);
		if($this->isError($result)) {
			throw new Exception($result->getMessage() . " - " . $result->getUserInfo());
		}
		return $result;
	}
	
	public function drop_table($tbl) {
		$ddl = "DROP TABLE `$tbl`";
		$result = $this->execute_ddl($ddl);
		if($this->isError($result)) {
			trigger_error($result->getMessage() . " - " . $result->getUserInfo());
		}	
	}
	
	public function create_table($table_name, $options = array()) {
		return new MySQLTableDefinition($this, $table_name, $options);
	}
	
	public function quote_string($str) {
	 return mysql_real_escape_string($str); 
  }
	
	public function quote($value, $column) {
		$native = $this->native_datatype_types();
		if(array_key_exists($type, $native_types)) {
			$type_array = $native_types[$type];
			if(array_key_exists('mdb_type', $type_array)) {
				return $this->adapter->quote($value, $type_array['mdb_type']);				
			}
		}
		//fail-safe let the driver determine whether quoting is needed
		return $this->adapter->quote($value);
	}
	
	public function rename_table($name, $new_name) {
		if(empty($name)) {
			throw new ArgumentException("Missing original column name parameter");
		}
		if(empty($new_name)) {
			throw new ArgumentException("Missing new column name parameter");
		}
		$sql = sprintf("RENAME TABLE %s TO %s", $name, $new_name);
		return $this->execute_ddl($sql);
	}//create_table
	
	public function add_column($table_name, $column_name, $type, $options = array()) {
		if(empty($table_name)) {
			throw new ArgumentException("Missing table name parameter");
		}
		if(empty($column_name)) {
			throw new ArgumentException("Missing column name parameter");
		}
		if(empty($type)) {
			throw new ArgumentException("Missing type parameter");
		}
		//default types
		if(!array_key_exists('limit', $options)) {
			$options['limit'] = null;
		}
		if(!array_key_exists('precision', $options)) {
			$options['precision'] = null;
		}
		if(!array_key_exists('scale', $options)) {
			$options['scale'] = null;
		}
		$sql = sprintf("ALTER TABLE %s ADD `%s` %s", $table_name, $column_name, $this->type_to_sql($type,$options['limit'], $options['precision'], $options['scale']));
		$sql .= $this->add_column_options($options);
		return $this->execute_ddl($sql);
	}//add_column
	
	public function remove_column($table_name, $column_name) {
		$sql = sprintf("ALTER TABLE `%s` DROP COLUMN `%s`", $table_name, $column_name);
		return $this->execute_ddl($sql);
	}//remove_column
	
	public function rename_column($table_name, $column_name, $new_column_name) {
		if(empty($table_name)) {
			throw new ArgumentException("Missing table name parameter");
		}
		if(empty($column_name)) {
			throw new ArgumentException("Missing original column name parameter");
		}
		if(empty($new_column_name)) {
			throw new ArgumentException("Missing new column name parameter");
		}
		$column_info = $this->column_info($table_name, $column_name);
		$current_type = $column_info['type'];
		$sql =  sprintf("ALTER TABLE `%s` CHANGE `%s` `%s` %s", $table_name, $column_name, $new_column_name, $current_type);
		return $this->execute_ddl($sql);
	}//rename_column


	public function change_column($table_name, $column_name, $type, $options = array()) {
		if(empty($table_name)) {
			throw new ArgumentException("Missing table name parameter");
		}
		if(empty($column_name)) {
			throw new ArgumentException("Missing original column name parameter");
		}
		if(empty($type)) {
			throw new ArgumentException("Missing type parameter");
		}
		$column_info = $this->column_info($table_name, $column_name);
		//default types
		if(!array_key_exists('limit', $options)) {
			$options['limit'] = null;
		}
		if(!array_key_exists('precision', $options)) {
			$options['precision'] = null;
		}
		if(!array_key_exists('scale', $options)) {
			$options['scale'] = null;
		}
		$sql = sprintf("ALTER TABLE `%s` CHANGE `%s` `%s` %s", $table_name, $column_name, $column_name,  $this->type_to_sql($type,$options['limit'], $options['precision'], $options['scale']));
		$sql .= $this->add_column_options($options);
		return $this->execute_ddl($sql);
	}//change_column

	public function column_info($table, $column) {
		if(empty($table)) {
			throw new ArgumentException("Missing table name parameter");
		}
		if(empty($column)) {
			throw new ArgumentException("Missing original column name parameter");
		}
		try {
			$sql = sprintf("SHOW COLUMNS FROM `%s` LIKE '%s'", $table, $column);
			$this->logger->log($sql);
			$result = $this->get_db()->queryRow($sql, null, MDB2_FETCHMODE_ASSOC);
			//if($this->isError($result)) {
			//	throw new Exception($result->getMessage() . " - " . $result->getUserInfo());
			//}
			return $result;
		}catch(Exception $e) {
			return null;
		}
	}//column_info
	
	public function add_index($table_name, $column_name, $options = array()) {
		if(empty($table_name)) {
			throw new ArgumentException("Missing table name parameter");
		}
		if(empty($column_name)) {
			throw new ArgumentException("Missing column name parameter");
		}
		//unique index?
		if(is_array($options) && array_key_exists('unique', $options)) {
			$unique = true;
		} else {
			$unique = false;
		}
		//did the user specify an index name?
		if(is_array($options) && array_key_exists('name', $options)) {
			$index_name = $options['name'];
		} else {
			$index_name = NamingUtil::index_name($table_name, $column_name);
		}
		if(!is_array($column_name)) {
			$column_name = array($column_name);
		}
		$sql = sprintf("CREATE %sINDEX %s ON %s(%s)",
											$unique ? "UNIQUE " : "",
											$index_name, 
											$table_name,
											join(", ", $column_name));
		return $this->execute_ddl($sql);		
	}//add_index
	
	public function remove_index($table_name, $column_name, $options = array()) {
		if(empty($table_name)) {
			throw new ArgumentException("Missing table name parameter");
		}
		if(empty($column_name)) {
			throw new ArgumentException("Missing column name parameter");
		}
		//did the user specify an index name?
		if(is_array($options) && array_key_exists('name', $options)) {
			$index_name = $options['name'];
		} else {
			$index_name = NamingUtil::index_name($table_name, $column_name);
		}
		$sql = sprintf("DROP INDEX `%s` ON `%s`", $index_name, $table_name);		
		return $this->execute_ddl($sql);
	}

	public function has_index($table_name, $column_name, $options = array()) {
		if(empty($table_name)) {
			throw new ArgumentException("Missing table name parameter");
		}
		if(empty($column_name)) {
			throw new ArgumentException("Missing column name parameter");
		}
		//did the user specify an index name?
		if(is_array($options) && array_key_exists('name', $options)) {
			$index_name = $options['name'];
		} else {
			$index_name = NamingUtil::index_name($table_name, $column_name);
		}
		$indexes = $this->indexes($table_name);
		foreach($indexes as $idx) {
			if($idx['name'] == $index_name) {
				return true;
			}
		}
		return false;
	}//has_index
	
	public function indexes($table_name) {
		$sql = sprintf("SHOW KEYS FROM `%s`", $table_name);
		$this->logger->log($sql);
		$result = $this->get_db()->query($sql);
		if($this->isError($result)) {
			throw new Exception($result->getMessage() . " - " . $result->getUserInfo());
		}
		$indexes = array();
		$cur_idx = null;
		while($row = $result->fetchRow()) {
			if($cur_idx != $row[1]) {
				if($row[2] == "PRIMARY") {
					continue; //skip primary key
				}
				$cur_idx = $row[2];
				$indexes[] = array('name' => $row[2], 'unique' => (int)$row[1] == 1 ? false : true);
			}
		}
		return $indexes;
	}//has_index

	public function type_to_sql($type, $limit = null, $precision = null, $scale = null) {		
		$natives = $this->native_database_types();
		$native_type = $natives[$type];
		if( is_array($native_type) && array_key_exists('name', $native_type)) {
			$column_type_sql = $native_type['name'];
		} else {
			return $native_type;
		}
		if($type == "decimal") {
			//ignore limit, use precison and scale
			if($precision == null || array_key_exists('precision', $native_type)) {
				$precision = $native_type['precision'];
			}
			if($scale == null || array_key_exists('scale', $native_type)) {
				$scale = $native_type['scale'];
			}
			if($precision) {
				if($scale) {
					$column_type_sql .= sprintf("(%d, %d)", $precision, $scale);
				} else {
					$column_type_sql .= sprintf("(%d)", $precision);						
				}//scale
			} else {
				if($scale) {
					throw new ArgumentException("Error adding decimal column: precision cannot be empty if scale is specified");
				}
			}//precision			
		} else {
			//not a decimal column
			if($limit == null && array_key_exists('limit', $native_type)) {
				$limit = $native_type['limit'];
			}
			if($limit) {
				$column_type_sql .= sprintf("(%d)", $limit);
			}		
		}
		return $column_type_sql;
	}//type_to_sql
	
	public function add_column_options($options) {
		$sql = "";
		if(is_array($options) && array_key_exists('default', $options) && $options['default'] !== null) {
			if($this->is_sql_method_call($options['default'])) {
				//$default_value = $options['default'];
				throw new Exception("MySQL does not support function calls as default values, constants only.");
			} else {
				$default_value = sprintf("'%s'", $options['default']);
			}
			$sql .= sprintf(" DEFAULT %s", $default_value);
		}
		if(is_array($options) && array_key_exists('unsigned', $options) && $options['unsigned'] === true) {
			$sql .= " unsigned";
		}
		
		if(is_array($options) && array_key_exists('null', $options) && $options['null'] === false) {
			$sql .= " NOT NULL";
		}
		return $sql;
	}//add_column_options
	
	public function set_current_version($version) {
		$sql = sprintf("UPDATE schema_info SET version = %d", $version);		
		return $this->execute_ddl($sql);
	}
	
	public function __toString() {
		return "MySQLAdapter, version " . $this->version;
	}

	
	//-----------------------------------
	// PRIVATE METHODS
	//-----------------------------------	
	private function connect($dsn) {
		$ref = &MDB2::connect($dsn);
		$this->set_db($ref);
		if($this->isError($this->get_db())) {
		    trigger_error($this->get_db()->getMessage());
		}
	}
	

	
	//Delegate to PEAR
	private function isError($o) {
		return PEAR::isError($o);
	}
	
	private function load_tables($reload = true) {
		if($this->tables_loaded == false || $reload) {
			$this->tables = array(); //clear existing structure			
			$result = $this->get_db()->listTables();
			if($this->isError($result)) {
				trigger_error($result->getMessage());
			}
			foreach($result as $t) {
				$this->tables[$t] = true;
			}
		}
	}
	
	private function determine_query_type($query) {
		$query = strtolower(trim($query));
		
		if(preg_match('/^select/', $query)) {
			return SELECT;
		}
		if(preg_match('/^update/', $query)) {
			return UPDATE;
		}
		if(preg_match('/^delete/', $query)) {
			return DELETE;
		}
		if(preg_match('/^insert/', $query)) {
			return INSERT;
		}
		if(preg_match('/^alter/', $query)) {
			return ALTER;
		}
		if(preg_match('/^drop/', $query)) {
			return DROP;
		}
		if(preg_match('/^create/', $query)) {
			return CREATE;
		}
		//else ...
		throw new Exception("could not determine query type for: $query");
	}
	
	private function is_select($query_type) {
		if($query_type == SELECT) {
			return true;
		}
		return false;
	}
	
	/*
		Detect whether or not the string represents a function call and if so
		do not wrap it in single-quotes, otherwise do wrap in single quotes.
	*/
	private function is_sql_method_call($str) {
		$str = trim($str);
		if(substr($str, -2, 2) == "()") {
			return true;			
		} else {
			return false;
		}
	}
	
	
}//class

?>