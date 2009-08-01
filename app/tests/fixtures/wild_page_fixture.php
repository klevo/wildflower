<?php 
class PageFixture extends CakeTestFixture {
    public $name = 'Page';
    public $import = 'Page';
    // ID 1 is home page
    public $records = array(
        array('id' => 1, 'parent_id' => null, 'lft' => 1, 'rght' => 2, 'level' => 0, 'slug' => 'article-1', 'url' => '/', 'title' => 'Article 1', 'content' => 'First Article Body', 'description_meta_tag' => 'Some description tag', 'keywords_meta_tag' => '', 'draft' => 0, 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31', 'user_id' => 1),
        array('id' => 2, 'parent_id' => 1, 'lft' => 3, 'rght' => 4, 'level' => 0, 'slug' => 'simple', 'url' => '/article-1/simple', 'title' => 'Simple', 'content' => 'SECOND Article Body', 'description_meta_tag' => 'Some description tag', 'keywords_meta_tag' => '', 'draft' => 0, 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31', 'user_id' => 1),
        array('id' => 3, 'parent_id' => 1, 'lft' => 5, 'rght' => 6, 'level' => 0, 'slug' => 'kratka-sprava', 'url' => '/article-1/kratka-sprava', 'title' => 'Krátka správa', 'content' => 'THIRD 234 čýľšíčíšľ&copy; Article Body', 'description_meta_tag' => 'Some description tag', 'keywords_meta_tag' => '', 'draft' => 1, 'created' => '2007-03-18 10:39:26', 'updated' => '2007-03-18 10:41:36', 'user_id' => 1),
    );
}
