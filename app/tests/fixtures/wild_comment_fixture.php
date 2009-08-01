<?php 
class CommentFixture extends CakeTestFixture {
    public $name = 'Comment';
    public $import = 'Comment';
    public $records = array(
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
}
