<?php

require_once 'PHPUnit/Framework.php';

require_once '../test_helper.php';
require_once RUCKUSING_BASE  . '/lib/classes/util/class.Ruckusing_VersionUtil.php';

define('RUCKUSING_TEST_HOME', RUCKUSING_BASE . '/tests');

 
class VersionUtilTest extends PHPUnit_Framework_TestCase {

		public function test_to_migration_number() {
      $this->assertEquals('003', Ruckusing_VersionUtil::to_migration_number(3) );
      $this->assertEquals('021', Ruckusing_VersionUtil::to_migration_number(21) );
      $this->assertEquals('099', Ruckusing_VersionUtil::to_migration_number(99) );
      $this->assertEquals('127', Ruckusing_VersionUtil::to_migration_number(127) );
		}

		public function test_get_highest_migration() {
      $this->assertEquals(3, Ruckusing_VersionUtil::get_highest_migration(RUCKUSING_MIGRATION_DIR) );
		}

}
?>