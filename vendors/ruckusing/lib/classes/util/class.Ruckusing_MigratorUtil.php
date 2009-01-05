<?php

class Ruckusing_MigratorUtil {
 
 function __construct($direction, $migrations_path, $target_version = null) {
 }

	/*
		Return a set of migration files, according to the given direction.
		If nested, then return a complex array with the migration parts broken up into parts
		which make analysis much easier.
	*/
	public static function get_migration_files($direction, $directory, $nested = false) { 
   $valid_files = array();
  	if(!is_dir($directory)) {
  	  die("Ruckusing_MigratorUtil - ({$dir}) is not a directory.");
  	}
  	$files = scandir($directory);
  	$file_cnt = count($files);
  	if($file_cnt > 0) {
  		for($i = 0; $i < $file_cnt; $i++) {
  			if(preg_match('/^(\d+)_(.*)\.php$/', $files[$i], $matches)) {
  				if(count($matches) == 3) {
  				  //echo "MATCHED; {$files[$i]}\n";
  				  $valid_files[] = $files[$i];
  				}//if-matches
        }//if-preg-match
  		}//for
  	}//if-file-cnt		
  	if($direction == 'down') {
  	  $valid_files = array_reverse($valid_files);
	  }
		if($nested == false) {
			return $valid_files;
		}
		
		//user wants a nested structure
		$files = array();
		$cnt = count($valid_files);
		for($i = 0; $i < $cnt; $i++) {
			$migration = $valid_files[$i];
			if(preg_match('/^(\d+)_(\w+)\.php$/', $migration, $matches)) {
				$files[] = array(
										'version' => (int)$matches[1],
										'class' 	=> $matches[2],
										'file'		=> $matches[0]
									);					
			}
		}//for
		return $files;
  }//get_migration_files
 
	public static function migration_files($directory, $direction, $current, $destination) {
		$migrations = self::get_migration_files($direction, $directory, true);
		if(empty($migrations)) {
			throw new Exception("Error: not able to get migrations in $directory");
		}
		return self::get_relevant_files($direction, $migrations, $current, $destination);
	}//migrations

	public static function get_relevant_files($direction, $files, $current, $destination) {
		$cnt = count($files);
		$valid = array();
		for($i = 0; $i < $cnt; $i++) {
			$set = $files[$i];
			if( count($set) != 3) {
				continue;
			}
			$cur_file_version = $set['version'];

			if($direction == 'up') {
				//echo "({$set['file']})cur = $cur_file_version, current = $current, destination = $destination\n";
				if($cur_file_version > $current && $cur_file_version <= $destination) {
					$valid[] = $set;
				}
			}//up
			
			if($direction == 'down') {
				//echo "$cur_file_version <= $current && $cur_file_version >= $destination\n";
				if($cur_file_version <= $current && $cur_file_version > $destination) {
					$valid[] = $set;
				}
			}//down
		}//for
		return $valid;
	}//get_relevant_files

}

?>