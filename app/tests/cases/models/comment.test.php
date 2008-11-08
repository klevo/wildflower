<?php 
App::import('Model', 'Comment');

class CommentTest extends Comment {
    public $useDbConfig = 'test';
    public $name = 'CommentTest';
    public $cacheSources = false;
}

class CommentTestCase extends CakeTestCase {
    public $fixtures = array('comment_test');
    
    function testFindAll() {
    	$comment = new CommentTest();
    	$result = $comment->findAll();
    	$expected = array(
	        array(
	            'id' => 1,
	            'post_id' => 1,
	            'name' => 'klevo',
	            'email' => 'someone@something.com',
	            'url' => '',
	            'content' => 'Comment text. ABC...',
	            'spam' => 0,
	            'created' => '2008-04-18 10:41:31',
	            'updated' => '2008-04-18 10:41:31'
	        ),
	         array(
	            'id' => 2,
	            'post_id' => 2,
	            'name' => 'Safir Tiges',
	            'email' => 'someone@tiger.net',
	            'url' => 'www.tiger.net',
	            'content' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
	            'spam' => 0,
	            'created' => '2008-05-18 10:41:31',
	            'updated' => '2008-06-18 10:41:31'
	        ),
	         array(
	            'id' => 4,
	            'post_id' => 1,
	            'name' => 'čšľáýčšľžáý ŤžŽ25122',
	            'email' => 'číľýčýšľíá@čľšíáýčýľíš.čšľ',
	            'url' => '',
	            'content' => 'čýšľčíáýľš ýščíýľáščýáíš=ľ+š=í+ľ éľšáý789428739 (/)!(/_ "!!. -a-sad.- as62183',
	            'spam' => 1,
	            'created' => '2008-04-18 12:41:31',
	            'updated' => '2008-04-18 15:41:31'
	        )
	    );
	    $result = Set::extract($result, '{n}.CommentTest');
	    $this->assertEqual($result, $expected);
    }
    
    function testValidSave() {
    	$data = array(
                'post_id' => 2,
                'name' => '<strong>VALID</strong> <script>alert("hello");</script> new comment',
                'email' => 'bananova_repuplika@hotmail.com',
                'url' => 'www.banany-su-zdrave.sk',
                'content' => 'Some english text.'
            );
        $comment = new CommentTest();
        $comment->spamCheck = false;
        $result = $comment->save($data);
        unset($result['CommentTest']['created'], $result['CommentTest']['updated']);
        $expected = array(
                'post_id' => 2,
                'name' => 'VALID alert("hello"); new comment',
                'email' => 'bananova_repuplika@hotmail.com',
                'url' => 'http://www.banany-su-zdrave.sk',
                'content' => 'Some english text.',
                'spam' => 0
            );
        $this->assertEqual($expected, $result['CommentTest']);
    }
    
    function testInvalidSave() {
    	$comment = new CommentTest();
        $comment->spamCheck = false;
        
    	$data = array(
                'post_id' => 2,
                'name' => 'inVALID new comment',
                'email' => 'this is not an email address',
                'url' => 'www.banany-su-zdrave.sk',
                'content' => 'Some english text.'
            );
        
        $result = $comment->save($data);
        $this->assertFalse($result);
        
        $data = array(
                'post_id' => 2,
                'name' => '',
                'email' => 'a@address.com',
                'url' => 'www.banany-su-zdrave.sk',
                'content' => 'Some english text.'
            );
        $result = $comment->save($data);
        $this->assertFalse($result);
        
        $data = array(
                'post_id' => 2,
                'name' => 'asdas asd as',
                'email' => 'a@address.com',
                'url' => 'www.banany-su-zdrave.sk',
                'content' => '   '
            );
        $result = $comment->save($data);
        $this->assertFalse($result);
        
        $data = array(
                'post_id' => 20,
                'name' => '<script></script>',
                'email' => 'a@address.com',
                'url' => 'www.banany-su-zdrave.sk',
                'content' => ' sa '
            );
        $result = $comment->save($data);
        $this->assertFalse($result);
    }
    
    function testCommentDelete() {
    }
    
}
