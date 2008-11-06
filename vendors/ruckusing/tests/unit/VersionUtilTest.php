<?php

require_once 'PHPUnit/Framework.php';

require_once '../test_helper.php';
require_once BASE  . '/lib/classes/util/class.VersionUtil.php';

define('TEST_HOME', BASE . '/tests');

 
class VersionUtilTest extends PHPUnit_Framework_TestCase {

		public function test_to_migration_number() {
      $this->assertEquals('003', VersionUtil::to_migration_number(3) );
      $this->assertEquals('021', VersionUtil::to_migration_number(21) );
      $this->assertEquals('099', VersionUtil::to_migration_number(99) );
      $this->assertEquals('127', VersionUtil::to_migration_number(127) );
		}

		public function test_get_highest_migration() {
      $this->assertEquals(3, VersionUtil::get_highest_migration(MIGRATION_DIR) );
		}

}
?>