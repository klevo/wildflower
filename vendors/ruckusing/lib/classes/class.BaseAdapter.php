<?php

class BaseAdapter {
	private $dsn;
	private $db;
	private $conn;
	
	function __construct($dsn) {
		$this->set_dsn($dsn);
	}
	
	public function set_dsn($dsn) { 
		$this->dsn = $dsn;
	}
	public function get_dsn() {
		return $this->dsn;
	}	

	public function set_db($db) { 
		$this->db = $db;
	}
	public function get_db() {
		return $this->db;
	}	
	
	public function set_logger($logger) {
		$this->logger = $logger;
	}

	public function get_logger($logger) {
		return $this->logger;
	}
	
	//alias
	public function has_table($tbl) {
		return $this->table_exists($tbl);
	}
	
	//Turn a DSN string into its constituent parts (e.g. host, user, etc)
	public function dsn_to_array($dsn = '') {
	  $dsn_to_parse = '';
	  //are we parsing the DSN that has been set previously or one from arguments?
	  if(!empty($dsn)) {
	    $dsn_to_parse = $dsn;
    } else {
      $dsn_to_parse = $this->get_dsn();
    }
	  $re = '/^(\w+):\/\/(\w+):(\w+)?@([\w\.\_\-]+)(:\d+)?\/(\w+)$/';
	  $matches = array();
	  if(preg_match($re, $dsn_to_parse, $matches)) {
	    $num_parts = count($matches);
	    if($num_parts == 7) { 
	      $info = array('type' => $matches[1], 'user' => $matches[2], 'password' => $matches[3],'host' => $matches[4]);
	      $info['database'] = $matches[6];
	      if(!empty($matches[5])) {
          $info['port'] = substr($matches[5], 1); //ditch the leading ":"
        }	   
  	    return $info;
      }
    }
    return NULL;
  } // dsn_to_parse()
	
	
}
?>
