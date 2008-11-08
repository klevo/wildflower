<?php 
class PageTestFixture extends CakeTestFixture {
    public $name = 'PageTest';
    public $fields = array(
        'id' => array('type' => 'integer', 'extra' => 'auto_increment', 'key' => 'primary'),
        'parent_id' => array('type' => 'integer', 'null' => true),
        'lft' => array('type' => 'integer', 'null' => true),
        'rght' => array('type' => 'integer', 'null' => true),
        'level' => array('type' => 'integer', 'default' => 0),
        'slug' => array('type' => 'string', 'null' => false),
        'url' => array('type' => 'string', 'null' => false),
        'title' => array('type' => 'string', 'null' => false),
        'content' => 'text',
        'description_meta_tag' => array('type' => 'string', 'null' => true),
        'keywords_meta_tag' => array('type' => 'string', 'null' => true),
        'draft' => array('type' => 'integer', 'length' => 1, 'default' => 0),
        'created' => 'datetime',
        'updated' => 'datetime'
    );
    // ID 1 is home page
    public $records = array(
        array('id' => 1, 'parent_id' => null, 'lft' => 1, 'rght' => 2, 'level' => 0, 'slug' => 'article-1', 'url' => '/', 'title' => 'Article 1', 'content' => 'First Article Body', 'description_meta_tag' => 'Some description tag', 'keywords_meta_tag' => '', 'draft' => 0, 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'),
        array('id' => 2, 'parent_id' => 1, 'lft' => 3, 'rght' => 4, 'level' => 0, 'slug' => 'simple', 'url' => '/article-1/simple', 'title' => 'Simple', 'content' => 'SECOND Article Body', 'description_meta_tag' => 'Some description tag', 'keywords_meta_tag' => '', 'draft' => 0, 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'),
        array('id' => 3, 'parent_id' => 1, 'lft' => 5, 'rght' => 6, 'level' => 0, 'slug' => 'kratka-sprava', 'url' => '/article-1/kratka-sprava', 'title' => 'Krátka správa', 'content' => 'THIRD 234 čýľšíčíšľ&copy; Article Body', 'description_meta_tag' => 'Some description tag', 'keywords_meta_tag' => '', 'draft' => 1, 'created' => '2007-03-18 10:39:26', 'updated' => '2007-03-18 10:41:36'),
    );
}
