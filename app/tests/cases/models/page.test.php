<?php 
App::import('Model', 'Page');

class PageTest extends Page {
    public $name = 'PageTest';
    public $useDbConfig = 'test';
    public $cacheSources = false;
}

// Revision mockup class
class Revision {
	function find() {
		return array();
	}
	function save($data) {
		return $data;
	}
}

class PageTestCase extends CakeTestCase {
    public $fixtures = array('page_test');
    private $PageTest;
    
    function startTest() {
    	Configure::write('AppSettings.home_page_id', 1);
    	$this->PageTest = new PageTest();
    }
    
    function endTest() {
    	unset($this->PageTest);
    }
    
    function testFindAll() {
        $result = $this->PageTest->findAll();
        
        // We need the same format as expected results
        $result = Set::extract($result, "{n}.PageTest");
        $expected = array(
            array('id' => 1, 'parent_id' => null, 'lft' => 1, 'rght' => 2, 'level' => 0, 'slug' => 'article-1', 'url' => '/', 'title' => 'Article 1', 'content' => 'First Article Body', 'description_meta_tag' => 'Some description tag', 'keywords_meta_tag' => '', 'draft' => 0, 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'),
            array('id' => 2, 'parent_id' => 1, 'lft' => 3, 'rght' => 4, 'level' => 0, 'slug' => 'simple', 'url' => '/article-1/simple', 'title' => 'Simple', 'content' => 'SECOND Article Body', 'description_meta_tag' => 'Some description tag', 'keywords_meta_tag' => '', 'draft' => 0, 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'),
            array('id' => 3, 'parent_id' => 1, 'lft' => 5, 'rght' => 6, 'level' => 0, 'slug' => 'kratka-sprava', 'url' => '/article-1/kratka-sprava', 'title' => 'Krátka správa', 'content' => 'THIRD 234 čýľšíčíšľ&copy; Article Body', 'description_meta_tag' => 'Some description tag', 'keywords_meta_tag' => '', 'draft' => 1, 'created' => '2007-03-18 10:39:26', 'updated' => '2007-03-18 10:41:36'),
        );
        $this->assertEqual($result, $expected);
    }
    
    function testGetSelectBoxData() {
        $result = $this->PageTest->getSelectBoxData(2);
        $expected = array(
            '1' => 'Article 1',
            '3' => 'Krátka správa');
        $this->assertEqual($result, $expected);
    }
    
    function testGetSelectBoxDataNoArg() {
        $result = $this->PageTest->getSelectBoxData();
        $expected = array(
            '1' => 'Article 1',
            '2' => 'Simple',
            '3' => 'Krátka správa');
        $this->assertEqual($result, $expected);
    }
    
    function testInvalidSave() {
        // Empty title
        $data['PageTest'] = array('parent_id' => null, 'title' => '', 'content' => 'First Article Body', 'draft' => 0);
        $this->PageTest->create($data);
        $saved = $this->PageTest->save();
        $this->assertFalse($saved);
        
        // No title present
        $data['PageTest'] = array('parent_id' => null, 'content' => 'First Article Body', 'draft' => 0);
        $this->PageTest->create($data);
        $saved = $this->PageTest->save();
        $this->assertFalse($saved);
        
        // Faked title
        $data['PageTest'] = array('parent_id' => null, 'title' => '   ', 'content' => 'First Article Body', 'draft' => 0);
        $this->PageTest->create($data);
        $saved = $this->PageTest->save();
        $this->assertFalse($saved);
    }
    
    function testValidSave() {
        $data['PageTest'] = array('parent_id' => null, 'title' => 'Vranné kone', 'content' => 'Lorem ipsum. Vranné kone.', 'draft' => 0);
        $saved = $this->PageTest->save($data);
        $this->assertTrue($saved);
        
        // Check generated slug and url
        $expectedSlug = 'vranne-kone';
        $expectedUrl = '/vranne-kone';
        $page = $this->PageTest->findById($this->PageTest->id);
        $this->assertEqual($page['PageTest']['slug'], $expectedSlug);
        $this->assertEqual($page['PageTest']['url'], $expectedUrl);
    }
    
    function testHomePageUpdate() {
        $data['PageTest'] = array('id' => 1, 'title' => 'Some longer title with number 467', 'slug' => 'some-longer-title-with-number-467', 'content' => '');
        $saved = $this->PageTest->save($data);
        $this->assertTrue($saved);
        
        // Check generated slug and url
        $expectedSlug = 'some-longer-title-with-number-467';
        $expectedUrl = '/'; // Remember it's home page
        $page = $this->PageTest->findById($this->PageTest->id);
        $this->assertEqual($page['PageTest']['slug'], $expectedSlug);
        $this->assertEqual($page['PageTest']['url'], $expectedUrl);
    }
    
    function testPageSaveWithExistingTitle() {
        $data['PageTest'] = array('title' => 'Article 1', 'content' => 'Lorem ipsum dolor sit amer. 123. čľž+áľšýí.11_?');
        $saved = $this->PageTest->save($data);
        //debug($this->PageTest->findById($this->PageTest->id));
        $expectedSlug = 'article-1-1'; // Slugable behavior should rename the slug like this
        $expectedUrl = '/article-1-1';
        $this->assertEqual($this->PageTest->field('slug'), $expectedSlug);
        $this->assertEqual($this->PageTest->field('url'), $expectedUrl);
    }
    
    function testPageSaveWithExistingTitleWithParentSet() {
        // One more try with parent page defined
        $data['PageTest'] = array('title' => 'Simple', 'parent_id' => 2, 'content' => 'Lorem ipsum dolor sit amer. 123. čľž+áľšýí.11_?');
        $saved = $this->PageTest->save($data);
        
        $expectedSlug = 'simple-1'; // Slugable behavior should rename the slug like this
        $expectedUrl = '/simple/simple-1';
        $this->assertEqual($saved['PageTest']['slug'], $expectedSlug);
        $this->assertEqual($saved['PageTest']['url'], $expectedUrl);
    }
    
    function testReparent() {
        $data['PageTest'] = array('id' => 2, 'title' => 'Simple', 'parent_id' => 3, 'content' => 'Lorem ipsum dolor sit amer. 123. čľž+áľšýí.11_?');
        $this->PageTest->save($data);
        
        $result = $this->PageTest->findById(2);
        // @TODO 
    }
}
