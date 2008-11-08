<?php 
App::import('Model', 'User');

class UserTest extends User {
    public $name = 'UserTest';
    public $useDbConfig = 'test';
    public $cacheSources = false;
}

class UserTestCase extends CakeTestCase {
    public $fixtures = array('user_test');
    private $UserTest;
    
    function startTest() {
    	$this->UserTest = new UserTest();
    }
    
    function endTest() {
    	unset($this->UserTest);
    }
    
    function testFindAll() {
        $result = $this->UserTest->findAll();
        $expected = array(
	        array('id' => 1, 
	              'login' => 'klevo', 
	              'password' => '9955d48db124ffd72e73e7400271e9d38e4b4358', // article123
	              'email' => 'klevoooo@klevo.sk',
	              'name' => 'Róbert Starší', 
	              'cookie' => '38499d8c-689c-7bf4-45fb-50dbf5edda61', 
	              'created' => '2007-08-14 11:28:22', 
	              'updated' => '2008-05-13 19:45:38'),
	        array('id' => 3, 
	              'login' => 'admin', 
	              'password' => '156e058b2b08882cf108b7711ae25d4221ae8e62',  // čýáíľššýíáč
	              'email' => 'admin@admin.com',
	              'name' => 'John Rambo', 
	              'cookie' => '38499d8c-689c-7bf4-45fb-50dbf5edda61', 
	              'created' => '2007-08-14 11:28:22', 
	              'updated' => '2008-05-13 19:45:38')
	    );
	    $result = Set::extract($result, '{n}.UserTest');
	    $this->assertEqual($expected, $result);
    }
    
    function testValidSave() {
    	$data['UserTest'] = array('name' => 'Pajtas', 'login' => 'pajtas',
    	   'email' => 'pajtas@klevo.sk', 
    	   'password' => 'hgfgha', 'confirm_password' => 'hgfgha');
        $this->UserTest->create($data);
        $result = $this->UserTest->save();
        
        unset($result['UserTest']['created'], $result['UserTest']['updated']);
        $data['UserTest']['password'] = sha1($data['UserTest']['password']);
        
        $this->assertEqual($data, $result);
    }
    
    function testInvalidSave() {
    	$data['UserTest'] = array('name' => 'Pajtas', 'login' => '_pajtas',
    	   'email' => 'pajtas@klevo.sk', 
    	   'password' => 'hgfgha', 'confirm_password' => 'hgfgha');
        $this->UserTest->create($data);
        $result = $this->UserTest->save();
        $this->assertFalse($result);
        
        $data['UserTest'] = array('name' => 'Pajtas', 'login' => 'pajtas',
           'email' => 'pajtas@klevo.sk', 
           'password' => 'hgfgha123', 'confirm_password' => 'hgfgha');
        $this->UserTest->create($data);
        $result = $this->UserTest->save();
        $this->assertFalse($result);
        
        $data['UserTest'] = array('name' => 'Pajtas', 'login' => '<>',
           'email' => 'pajtas@klevo.sk', 
           'password' => 'hgfgha123', 'confirm_password' => 'hgfgha');
        $this->UserTest->create($data);
        $result = $this->UserTest->save();
        $this->assertFalse($result);
    }
    
    function testSuccefulAuthenticate() {
    	$result = $this->UserTest->authenticate('klevo', 'article123');
    	$this->assertTrue(!empty($result));
    	
    	$result = $this->UserTest->authenticate('admin', 'čýáíľššýíáč');
    	$this->assertTrue(!empty($result));
    }
    
    function testUnsuccefulAuthenticate() {
    	$result = $this->UserTest->authenticate('klevo_', 'article123');
    	$this->assertTrue(empty($result));
    	
        $result = $this->UserTest->authenticate('admin', ' čýáíľššýíáč');
        $this->assertTrue(empty($result));
    }
    
    function testSaveAndAuthenticate() {
    	// Create a new user
    	$data['UserTest'] = array('name' => 'Uriah Heep', 'login' => 'uriah',
           'email' => 'uriah@heep.tk', 
           'password' => 'never2late', 'confirm_password' => 'never2late');
        $result = $this->UserTest->save($data);
        
    	// Try to authenticate
    	$result = $this->UserTest->authenticate('uriah', 'never2late');
        $this->assertTrue(!empty($result));
    }
}
