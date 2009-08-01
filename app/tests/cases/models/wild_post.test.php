<?php 
class PostTestCase extends CakeTestCase {
    public $fixtures = array(
        'post', 
        'user',  
        'category_test', 
        'categories_post_test', 
        'comment_test',
    );
    private $Post;
    
    function startTest() {
        $this->Post = ClassRegistry::init('Post');
        $this->Post->Behaviors->detach('Versionable');
        $this->assertTrue($this->Post);
    }
    
    function endTest() {
        unset($this->Post);
    }
    
    function testFindAllNonRecursive() {
        $result = $this->Post->find('all', array('recursive' => -1));
        $result = Set::extract($result, "{n}.Post");
        $expected = array(
	        array('id' => 1, 'slug' => 'some-nice-test-post', 'title' => 'Some nice test post', 'content' => 'First Article Body', 'user_id' => 1, 'description_meta_tag' => '', 'keywords_meta_tag' => '', 'created' => '2008-03-18 10:39:23', 'updated' => '2008-04-18 10:41:31'),
	        array('id' => 2, 'slug' => 'alcohol', 'title' => 'A few beers and vodka', 'content' => 'First Article Body', 'user_id' => 1, 'description_meta_tag' => '', 'keywords_meta_tag' => '', 'created' => '2008-03-18 10:39:23', 'updated' => '2008-04-18 10:41:31'),
	        array('id' => 3, 'slug' => 'strings-are-fuuun', 'title' => 'Strings are fúúún!', 'content' => 'First Article Body', 'user_id' => 1, 'description_meta_tag' => '', 'keywords_meta_tag' => '', 'created' => '2008-03-18 10:39:23', 'updated' => '2008-04-18 10:41:31'),
	    );
	    $this->assertEqual($result, $expected);
    }
    
    function testAddNewPost() {
    	// Try 1
    	$data['Post'] = array('title' => 'Some nice test post', 
    	   'content' => 'First Article Body', 'description_meta_tag' => '', 
    	   'keywords_meta_tag' => ''); 
    	$this->Post->save($data);
    	$this->assertEqual($this->Post->id, 4);
    	
        // Try 2    	
    	$data['Post'] = array('title' => 'Nejaký slovenský text...', 
           'content' => '', 'description_meta_tag' => 'About nothing', 
           'keywords_meta_tag' => 'google noodle');
    	$this->Post->create($data);
    	$this->Post->save();
    	$this->assertEqual($this->Post->id, 5);
    }
    
    function testInvalidSave() {
        $data['Post'] = array('title' => '', 
           'content' => 'First Article Body', 'description_meta_tag' => '', 
           'keywords_meta_tag' => '');
        $success = $this->Post->save($data);
        $this->assertEqual($success, false);
    }
    
    function testSlugCreation() {
        $data['Post'] = array('title' => 'Nejaký slovenský text...', 
           'content' => '', 'description_meta_tag' => 'About nothing', 
           'keywords_meta_tag' => 'google noodle');
        $this->Post->save($data);
        $this->Post->recursive = -1;
        $savedPost = $this->Post->findById($this->Post->id);
        $this->assertEqual($savedPost['Post']['slug'], 'nejaky-slovensky-text');
    }
    
    function testPostDelete() {
    	$this->Post->recursive = -1;
    	$this->Post->delete(2);
    	$expected = array(
            array('id' => 1, 'slug' => 'some-nice-test-post', 'title' => 'Some nice test post', 'content' => 'First Article Body', 'user_id' => 1, 'description_meta_tag' => '', 'keywords_meta_tag' => '', 'created' => '2008-03-18 10:39:23', 'updated' => '2008-04-18 10:41:31'),
            array('id' => 3, 'slug' => 'strings-are-fuuun', 'title' => 'Strings are fúúún!', 'content' => 'First Article Body', 'user_id' => 1, 'description_meta_tag' => '', 'keywords_meta_tag' => '', 'created' => '2008-03-18 10:39:23', 'updated' => '2008-04-18 10:41:31'),
        );
        $result = $this->Post->find('all', array('recursive' => -1));
        $result = Set::extract($result, "{n}.Post");
        $this->assertEqual($result, $expected);
    }
    
    function testFindAllByCategory() {
    	//$result = $this->Post->findAllByCategory('cakephp');
    	//debug($result);
    }
    
}
