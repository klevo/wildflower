<?php

/*
  This utility class helps with parsing the migration directory and obtaining such
  information as the highest migration (so we know whats "next") and other related information.

*/

class Ruckusing_VersionUtil {
  
  /*
    Returns an integer representing the highest migration file in the specified directory.
    E.g. if the directory contained 3 migrations named "001_CreatUsers.php", "002_AddIndexToAnimals.php" and "003_DropTablePeople.php"
    then this method would return 3. 
    
    NOTE: the number returned is NOT just the absolute /number/ of migration files as its possible to have non-contiguous
    names. That is, you could have files such as "001_CreateUsers.php" and "003_DropTablePeople.php", notice there is no "002_Foo.php".
    As its not strictly necessary to be contiguous.
  */
  public static function get_highest_migration($migration_dir) {
		$maxima = 0;
    $regexp = '/^(\d+)\_/';
    if(!is_dir($migration_dir)) {
			throw new Exception("Migration directory ({$migration_dir}) does not exist.");
		}
		$files = scandir($migration_dir,1); //sort in descending order
		$len = count($files);
		for($i = 0; $i < $len; $i++) {
			if($files[$i] == "." || $files[$i] == "..") { continue; }
			if(preg_match($regexp, $files[$i], $matches)) {
				if(count($matches) == 2 && $matches[1] > $maxima) {
					$maxima = (int)$matches[1];
				}
			}			
		}
		return $maxima;
  }
  
  /*
    Convert an integer into the numeric portion (prefix) of a migration name. E.g. turn 1 into "001"
    or 20 into "020", 123 into "123"
    
    The returntype is a string although it does represent the numeric portion of the file name.    
  */
  public static function to_migration_number($num) {
    return sprintf("%03d", $num);
  }//to_migration_number
  
}

?>