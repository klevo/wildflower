<?php 
class CommentTestCase extends CakeTestCase {
    public $fixtures = array(
        'comment', 
        'post',
        'page',
        'revision',
        'user',
        'category',
        'category_post',
    );
    private $Comment;
    
    function startTest() {
        $this->Comment = ClassRegistry::init('Comment');
    }
    
    function endTest() {
        unset($this->Comment);
    }

    function testValidSave() {
        $data = array(
            'post_id' => 2,
            'name' => '<strong>VALID</strong> <script>alert("hello");</script> new comment',
            'email' => 'bananova_repuplika@hotmail.com',
            'url' => 'www.banany-su-zdrave.sk',
            'content' => 'Some english text.'
            );
        
        $this->Comment->spamCheck = false;
        $result = $this->Comment->save($data);
        unset($result[$this->Comment->name]['created'], $result[$this->Comment->name]['updated']);
        $expected = array(
            'post_id' => 2,
            'name' => 'VALID alert("hello"); new comment',
            'email' => 'bananova_repuplika@hotmail.com',
            'url' => 'http://www.banany-su-zdrave.sk',
        'content' => 'Some english text.',
            'spam' => 0
            );
        $this->assertEqual($expected, $result[$this->Comment->name]);
    }

    function testInvalidSave() {
        $this->Comment->spamCheck = false;

        $data = array(
            'post_id' => 2,
            'name' => 'inVALID new comment',
            'email' => 'this is not an email address',
            'url' => 'www.banany-su-zdrave.sk',
            'content' => 'Some english text.'
            );

        $result = $this->Comment->save($data);
        $this->assertFalse($result);

        $data = array(
            'post_id' => 2,
            'name' => '',
            'email' => 'a@address.com',
            'url' => 'www.banany-su-zdrave.sk',
            'content' => 'Some english text.'
            );
        $result = $this->Comment->save($data);
        $this->assertFalse($result);

        $data = array(
            'post_id' => 2,
            'name' => 'asdas asd as',
            'email' => 'a@address.com',
            'url' => 'www.banany-su-zdrave.sk',
            'content' => '   '
            );
        $result = $this->Comment->save($data);
        $this->assertFalse($result);

        $data = array(
            'post_id' => 20,
            'name' => '<script></script>',
            'email' => 'a@address.com',
            'url' => 'www.banany-su-zdrave.sk',
            'content' => ' sa '
            );
        $result = $this->Comment->save($data);
        $this->assertFalse($result);
    }

    function testCommentDelete() {
    }

}
