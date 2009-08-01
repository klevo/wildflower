<?php
require_once(APP . 'config' . DS . 'routes.php');

class PageTestCase extends CakeTestCase {
    public $fixtures = array(
        'page',
        'user',
        'revision',
        'post',
        'comment',
        'category',
        'category_post',
        'setting',
    );
    private $Page;
    
    function startTest() {
        Configure::write('AppSettings.home_page_id', 1);
    	$this->Page = ClassRegistry::init('Page');
    }
    
    function endTest() {
    	unset($this->Page);
    }
    
    function testUseDbConfig() {
        $this->assertEqual($this->Page->useDbConfig, 'test_suite');
        $this->assertEqual($this->Page->User->useDbConfig, 'test_suite');
    }
    
    function testGetListThreadedWithSkipId() {
        $result = $this->Page->getListThreaded(2);
        $expected = array(
            '1' => 'Article 1',
            '3' => 'Krátka správa');
        $this->assertEqual($result, $expected);
    }
    
    function testGetListThreaded() {
        $result = $this->Page->getListThreaded();
        $expected = array(
            '1' => 'Article 1',
            '2' => 'Simple',
            '3' => 'Krátka správa');
        $this->assertEqual($result, $expected);
    }
    
    function testInvalidSave() {
        // Empty title
        $data[$this->Page->name] = array('parent_id' => null, 'title' => '', 'content' => 'First Article Body', 'draft' => 0);
        $this->Page->create($data);
        $saved = $this->Page->save();
        $this->assertFalse($saved);
        
        // No title present
        $data[$this->Page->name] = array('parent_id' => null, 'content' => 'First Article Body', 'draft' => 0);
        $this->Page->create($data);
        $saved = $this->Page->save();
        $this->assertFalse($saved);
        
        // Faked title
        $data[$this->Page->name] = array('parent_id' => null, 'title' => '   ', 'content' => 'First Article Body', 'draft' => 0);
        $this->Page->create($data);
        $saved = $this->Page->save();
        $this->assertFalse($saved);
    }
    
    function testValidSave() {
        $data[$this->Page->name] = array('parent_id' => null, 'title' => 'Vranné kone', 'content' => 'Lorem ipsum. Vranné kone.', 'draft' => 0);
        $saved = $this->Page->save($data);
        $this->assertTrue($saved);
        
        // Check generated slug and url
        $expectedSlug = 'vranne-kone';
        $expectedUrl = '/vranne-kone';
        $page = $this->Page->findById($this->Page->id);
        $this->assertEqual($page[$this->Page->name]['slug'], $expectedSlug);
        $this->assertEqual($page[$this->Page->name]['url'], $expectedUrl);
    }
    
    function testHomePageUpdate() {
        $data[$this->Page->name] = array('id' => 1, 'title' => 'Some longer title with number 467', 'slug' => 'some-longer-title-with-number-467', 'content' => '');
        $saved = $this->Page->save($data);
        $this->assertTrue($saved);
        
        // Check generated slug and url
        $expectedSlug = 'some-longer-title-with-number-467';
        $expectedUrl = '/'; // Remember it's home page
        $page = $this->Page->findById($this->Page->id);
        $this->assertEqual($page[$this->Page->name]['slug'], $expectedSlug);
        $this->assertEqual($page[$this->Page->name]['url'], $expectedUrl);
    }
    
    function testPageSaveWithExistingTitle() {
        $data[$this->Page->name] = array('title' => 'Article 1', 'content' => 'Lorem ipsum dolor sit amer. 123. čľž+áľšýí.11_?');
        $saved = $this->Page->save($data);
        //debug($this->Page->findById($this->Page->id));
        $expectedSlug = 'article-1-1'; // Slugable behavior should rename the slug like this
        $expectedUrl = '/article-1-1';
        $this->assertEqual($this->Page->field('slug'), $expectedSlug);
        $this->assertEqual($this->Page->field('url'), $expectedUrl);
    }
    
    function testPageSaveWithExistingTitleWithParentSet() {
        // One more try with parent page defined
        $data[$this->Page->name] = array('title' => 'Simple', 'parent_id' => 2, 'content' => 'Lorem ipsum dolor sit amer. 123. čľž+áľšýí.11_?');
        $saved = $this->Page->save($data);
        
        $expectedSlug = 'simple-1'; // Slugable behavior should rename the slug like this
        $expectedUrl = '/simple/simple-1';
        $saved = $this->Page->read();

        $this->assertEqual($saved[$this->Page->name]['slug'], $expectedSlug);
        $this->assertEqual($saved[$this->Page->name]['url'], $expectedUrl);
    }
    
    function testReparent() {
        $data[$this->Page->name] = array('id' => 2, 'title' => 'Simple', 'parent_id' => 3, 'content' => 'Lorem ipsum dolor sit amer. 123. čľž+áľšýí.11_?');
        $this->Page->save($data);
        
        $result = $this->Page->findById(2);
        // @TODO 
    }
}
