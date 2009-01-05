<?php

require_once 'PHPUnit/Framework.php';

require_once '../test_helper.php';
require_once RUCKUSING_BASE  . '/lib/classes/util/class.Ruckusing_MigratorUtil.php';

define('RUCKUSING_TEST_HOME', RUCKUSING_BASE . '/tests');

 
class MigratorUtilTest extends PHPUnit_Framework_TestCase {

		public function test_get_migration_files_non_nested() {
		  $actual_up_files = Ruckusing_MigratorUtil::get_migration_files('up', RUCKUSING_MIGRATION_DIR, false);
		  $actual_down_files = Ruckusing_MigratorUtil::get_migration_files('down', RUCKUSING_MIGRATION_DIR, false);
		  
		  $expect_up_files = array('001_CreateUsers.php', '003_AddIndexToBlogs.php');
		  $expect_down_files = array_reverse($expect_up_files);
		  
      $this->assertEquals($expect_up_files, $actual_up_files);
      $this->assertEquals($expect_down_files, $actual_down_files);
		}

		public function test_get_migration_files_nested() {
		  $actual_up_files = Ruckusing_MigratorUtil::get_migration_files('up', RUCKUSING_MIGRATION_DIR, true);
		  $actual_down_files = Ruckusing_MigratorUtil::get_migration_files('down', RUCKUSING_MIGRATION_DIR, true);
		  
		  $expect_up_files = array(
														array(
															'version' => 1,
															'class' 	=> 'CreateUsers',
															'file'		=> '001_CreateUsers.php'
														),
														array(
															'version' => 3,
															'class' 	=> 'AddIndexToBlogs',
															'file'		=> '003_AddIndexToBlogs.php'
														)
													);
		  $expect_down_files = array_reverse($expect_up_files);
		  
      $this->assertEquals($expect_up_files, $actual_up_files);
      $this->assertEquals($expect_down_files, $actual_down_files);
		}

		public function test_get_relevant_migration_files_up() {
		  $up_files = Ruckusing_MigratorUtil::get_migration_files('up', RUCKUSING_MIGRATION_DIR, true);
		  
			$actual_up_files = Ruckusing_MigratorUtil::get_relevant_files('up', $up_files, 0, 2);
		  $expect_up_files = array(
														array(
															'version' => 1,
															'class' 	=> 'CreateUsers',
															'file'		=> '001_CreateUsers.php'
														)
													);
			$this->assertEquals($expect_up_files, $actual_up_files);

			$actual_up_files = Ruckusing_MigratorUtil::get_relevant_files('up', $up_files, 0, 3);
		  $expect_up_files = array(
														array(
															'version' => 1,
															'class' 	=> 'CreateUsers',
															'file'		=> '001_CreateUsers.php'
														),
														array(
															'version' => 3,
															'class' 	=> 'AddIndexToBlogs',
															'file'		=> '003_AddIndexToBlogs.php'
														)
													);
			$this->assertEquals($expect_up_files, $actual_up_files);

			$actual_up_files = Ruckusing_MigratorUtil::get_relevant_files('up', $up_files, 2, 3);
		  $expect_up_files = array(
														array(
															'version' => 3,
															'class' 	=> 'AddIndexToBlogs',
															'file'		=> '003_AddIndexToBlogs.php'
														)
													);
			$this->assertEquals($expect_up_files, $actual_up_files);
		}//test_get_relevant_migration_files_up

		public function test_get_relevant_migration_files_down() {
		  $down_files = Ruckusing_MigratorUtil::get_migration_files('down', RUCKUSING_MIGRATION_DIR, true);
		  
			$actual_down_files = Ruckusing_MigratorUtil::get_relevant_files('down', $down_files, 3, 0);
		  $expect_down_files = array(
														array(
															'version' => 3,
															'class' 	=> 'AddIndexToBlogs',
															'file'		=> '003_AddIndexToBlogs.php'
														),
														array(
															'version' => 1,
															'class' 	=> 'CreateUsers',
															'file'		=> '001_CreateUsers.php'
														)
																												
													);
			$this->assertEquals($expect_down_files, $actual_down_files);

			$actual_down_files = Ruckusing_MigratorUtil::get_relevant_files('down', $down_files, 2, 0);
		  $expect_down_files = array(
														array(
															'version' => 1,
															'class' 	=> 'CreateUsers',
															'file'		=> '001_CreateUsers.php'
														)
													);
			$this->assertEquals($expect_down_files, $actual_down_files);

			$actual_down_files = Ruckusing_MigratorUtil::get_relevant_files('down', $down_files, 3,2);
		  $expect_down_files = array(
														array(
															'version' => 3,
															'class' 	=> 'AddIndexToBlogs',
															'file'		=> '003_AddIndexToBlogs.php'
														)
													);
			$this->assertEquals($expect_down_files, $actual_down_files);
		}//test_get_relevant_migration_files_up




}
?>