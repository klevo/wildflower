<?php 
class WildPostTestCase extends CakeTestCase {
    public $fixtures = array(
        'wild_post', 
        'wild_user',  
        'category_test', 
        'categories_post_test', 
        'comment_test',
    );
    private $WildPost;
    
    function startTest() {
        $this->WildPost = ClassRegistry::init('WildPost');
        $this->WildPost->Behaviors->detach('Versionable');
        $this->assertTrue($this->WildPost);
    }
    
    function endTest() {
        unset($this->WildPost);
    }
    
    function testFindAllNonRecursive() {
        $result = $this->WildPost->find('all', array('recursive' => -1));
        $result = Set::extract($result, "{n}.WildPost");
        $expected = array(
	        array('id' => 1, 'slug' => 'some-nice-test-post', 'title' => 'Some nice test post', 'content' => 'First Article Body', 'user_id' => 1, 'description_meta_tag' => '', 'keywords_meta_tag' => '', 'created' => '2008-03-18 10:39:23', 'updated' => '2008-04-18 10:41:31'),
	        array('id' => 2, 'slug' => 'alcohol', 'title' => 'A few beers and vodka', 'content' => 'First Article Body', 'user_id' => 1, 'description_meta_tag' => '', 'keywords_meta_tag' => '', 'created' => '2008-03-18 10:39:23', 'updated' => '2008-04-18 10:41:31'),
	        array('id' => 3, 'slug' => 'strings-are-fuuun', 'title' => 'Strings are fúúún!', 'content' => 'First Article Body', 'user_id' => 1, 'description_meta_tag' => '', 'keywords_meta_tag' => '', 'created' => '2008-03-18 10:39:23', 'updated' => '2008-04-18 10:41:31'),
	    );
	    $this->assertEqual($result, $expected);
    }
    
    function testAddNewPost() {
    	// Try 1
    	$data['WildPost'] = array('title' => 'Some nice test post', 
    	   'content' => 'First Article Body', 'description_meta_tag' => '', 
    	   'keywords_meta_tag' => ''); 
    	$this->WildPost->save($data);
    	$this->assertEqual($this->WildPost->id, 4);
    	
        // Try 2    	
    	$data['WildPost'] = array('title' => 'Nejaký slovenský text...', 
           'content' => '', 'description_meta_tag' => 'About nothing', 
           'keywords_meta_tag' => 'google noodle');
    	$this->WildPost->create($data);
    	$this->WildPost->save();
    	$this->assertEqual($this->WildPost->id, 5);
    }
    
    function testInvalidSave() {
        $data['WildPost'] = array('title' => '', 
           'content' => 'First Article Body', 'description_meta_tag' => '', 
           'keywords_meta_tag' => '');
        $success = $this->WildPost->save($data);
        $this->assertEqual($success, false);
    }
    
    function testSlugCreation() {
        $data['WildPost'] = array('title' => 'Nejaký slovenský text...', 
           'content' => '', 'description_meta_tag' => 'About nothing', 
           'keywords_meta_tag' => 'google noodle');
        $this->WildPost->save($data);
        $this->WildPost->recursive = -1;
        $savedPost = $this->WildPost->findById($this->WildPost->id);
        $this->assertEqual($savedPost['WildPost']['slug'], 'nejaky-slovensky-text');
    }
    
    function testPostDelete() {
    	$this->WildPost->recursive = -1;
    	$this->WildPost->delete(2);
    	$expected = array(
            array('id' => 1, 'slug' => 'some-nice-test-post', 'title' => 'Some nice test post', 'content' => 'First Article Body', 'user_id' => 1, 'description_meta_tag' => '', 'keywords_meta_tag' => '', 'created' => '2008-03-18 10:39:23', 'updated' => '2008-04-18 10:41:31'),
            array('id' => 3, 'slug' => 'strings-are-fuuun', 'title' => 'Strings are fúúún!', 'content' => 'First Article Body', 'user_id' => 1, 'description_meta_tag' => '', 'keywords_meta_tag' => '', 'created' => '2008-03-18 10:39:23', 'updated' => '2008-04-18 10:41:31'),
        );
        $result = $this->WildPost->find('all', array('recursive' => -1));
        $result = Set::extract($result, "{n}.WildPost");
        $this->assertEqual($result, $expected);
    }
    
    function testFindAllByCategory() {
    	//$result = $this->WildPost->findAllByCategory('cakephp');
    	//debug($result);
    }
    
}
