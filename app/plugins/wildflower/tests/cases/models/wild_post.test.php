<?php 
App::import('Model', 'Post');

class PostTest extends Post {
    public $useDbConfig = 'test';
    public $name = 'PostTest';
    public $cacheSources = false;
}

//class CategoriesPostTest extends CakeTestModel {
//	public $useTable = 'categories_post_tests';
//}

class PostTestCase extends CakeTestCase {
    public $fixtures = array('post_test', 'category_test', 'categories_post_test', 'comment_test');
    private $PostTest;
    
    function startTest() {
        $this->PostTest = new PostTest();
        $this->PostTest->Behaviors->detach('Versionable');
    }
    
    function endTest() {
        unset($this->PostTest);
    }
    
    function testFindAllNonRecursive() {
        $result = $this->PostTest->find('all', array('recursive' => -1));
        $result = Set::extract($result, "{n}.PostTest");
        $expected = array(
	        array('id' => 1, 'slug' => 'some-nice-test-post', 'title' => 'Some nice test post', 'content' => 'First Article Body', 'user_id' => 1, 'description_meta_tag' => '', 'keywords_meta_tag' => '', 'created' => '2008-03-18 10:39:23', 'updated' => '2008-04-18 10:41:31'),
	        array('id' => 2, 'slug' => 'alcohol', 'title' => 'A few beers and vodka', 'content' => 'First Article Body', 'user_id' => 1, 'description_meta_tag' => '', 'keywords_meta_tag' => '', 'created' => '2008-03-18 10:39:23', 'updated' => '2008-04-18 10:41:31'),
	        array('id' => 3, 'slug' => 'strings-are-fuuun', 'title' => 'Strings are fúúún!', 'content' => 'First Article Body', 'user_id' => 1, 'description_meta_tag' => '', 'keywords_meta_tag' => '', 'created' => '2008-03-18 10:39:23', 'updated' => '2008-04-18 10:41:31'),
	    );
	    $this->assertEqual($result, $expected);
    }
    
    function testAddNewPost() {
    	// Try 1
    	$data['PostTest'] = array('title' => 'Some nice test post', 
    	   'content' => 'First Article Body', 'description_meta_tag' => '', 
    	   'keywords_meta_tag' => ''); 
    	$this->PostTest->save($data);
    	$this->assertEqual($this->PostTest->id, 4);
    	
        // Try 2    	
    	$data['PostTest'] = array('title' => 'Nejaký slovenský text...', 
           'content' => '', 'description_meta_tag' => 'About nothing', 
           'keywords_meta_tag' => 'google noodle');
    	$this->PostTest->create($data);
    	$this->PostTest->save();
    	$this->assertEqual($this->PostTest->id, 5);
    }
    
    function testInvalidSave() {
        $data['PostTest'] = array('title' => '', 
           'content' => 'First Article Body', 'description_meta_tag' => '', 
           'keywords_meta_tag' => '');
        $success = $this->PostTest->save($data);
        $this->assertEqual($success, false);
    }
    
    function testSlugCreation() {
        $data['PostTest'] = array('title' => 'Nejaký slovenský text...', 
           'content' => '', 'description_meta_tag' => 'About nothing', 
           'keywords_meta_tag' => 'google noodle');
        $this->PostTest->save($data);
        $this->PostTest->recursive = -1;
        $savedPost = $this->PostTest->findById($this->PostTest->id);
        $this->assertEqual($savedPost['PostTest']['slug'], 'nejaky-slovensky-text');
    }
    
    function testPostDelete() {
    	$this->PostTest->recursive = -1;
    	$this->PostTest->delete(2);
    	$expected = array(
            array('id' => 1, 'slug' => 'some-nice-test-post', 'title' => 'Some nice test post', 'content' => 'First Article Body', 'user_id' => 1, 'description_meta_tag' => '', 'keywords_meta_tag' => '', 'created' => '2008-03-18 10:39:23', 'updated' => '2008-04-18 10:41:31'),
            array('id' => 3, 'slug' => 'strings-are-fuuun', 'title' => 'Strings are fúúún!', 'content' => 'First Article Body', 'user_id' => 1, 'description_meta_tag' => '', 'keywords_meta_tag' => '', 'created' => '2008-03-18 10:39:23', 'updated' => '2008-04-18 10:41:31'),
        );
        $result = $this->PostTest->find('all', array('recursive' => -1));
        $result = Set::extract($result, "{n}.PostTest");
        $this->assertEqual($result, $expected);
    }
    
    function testFindAllByCategory() {
    	//$result = $this->PostTest->findAllByCategory('cakephp');
    	//debug($result);
    }
    
}
