<?php

require_once 'PHPUnit/Framework.php';

require_once '../test_helper.php';
require_once BASE  . '/lib/classes/util/class.NamingUtil.php';

define('TEST_HOME', BASE . '/tests');

 
class NamingUtilTest extends PHPUnit_Framework_TestCase
{
    public function test_task_from_class_method() {
				$klass = "DB_Schema";
        $this->assertEquals('db:schema', NamingUtil::task_from_class_name($klass) );
    }

    public function test_task_to_class_method() {
				$task_name = "db:schema";
        $this->assertEquals('DB_Schema', NamingUtil::task_to_class_name($task_name) );
    }

		public function test_class_name_from_file_name() {
			$klass = TEST_HOME . '/dummy/class.DB_Setup.php';
      $this->assertEquals('DB_Setup', NamingUtil::class_from_file_name($klass) );
		}

		public function test_class_name_from_string() {
			$klass = 'class.DB_Schema.php';
      $this->assertEquals('DB_Schema', NamingUtil::class_from_file_name($klass) );
		}

		public function test_class_from_migration_file_name() {
			$klass = '001_CreateUsers.php';
      $this->assertEquals('CreateUsers', NamingUtil::class_from_migration_file($klass) );

			$klass = '120_AddIndexToPeopleTable.php';
      $this->assertEquals('AddIndexToPeopleTable', NamingUtil::class_from_migration_file($klass) );
		}

		public function test_camelcase() {
			$a = "add index to users";
      $this->assertEquals('AddIndexToUsers', NamingUtil::camelcase($a) );

			$b = "add index to Users";
      $this->assertEquals('AddIndexToUsers', NamingUtil::camelcase($b) );

			$c = "AddIndexToUsers";
      $this->assertEquals('AddIndexToUsers', NamingUtil::camelcase($c) );
		}
		
		public function test_underscore() {
      $this->assertEquals("users_and_children", NamingUtil::underscore("users and children") );
      $this->assertEquals("animals", NamingUtil::underscore("animals") );
      $this->assertEquals("bobby_pins", NamingUtil::underscore("bobby!pins") );			
		}
		
		public function test_index_name() {
			$column = "first_name";
      $this->assertEquals("idx_users_first_name", NamingUtil::index_name("users", $column));

			$column = "age";
      $this->assertEquals("idx_users_age", NamingUtil::index_name("users", $column));

			$column = array('listing_id', 'review_id');
      $this->assertEquals("idx_users_listing_id_and_review_id", NamingUtil::index_name("users", $column));


		}
		

}
?>