<?php
require_once 'PHPUnit/Framework.php';
 
require_once '../test_helper.php';
require_once BASE  . '/lib/classes/task/class.TaskManager.php';

class TaskManagerTest extends PHPUnit_Framework_TestCase
{
		protected function setUp() {
		}
		
		public function testGetValidName()
    {
				global $db_config;
        // Create the Array fixture.
        $fixture = array();
				
				$mgr = new TaskManager($db_config);
 
        // Assert that the size of the Array fixture is 0.
        $this->assertEquals("Cody", $mgr->get_name());
    }
 
}
?>