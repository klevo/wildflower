<?php 
class CategoryPostFixture extends CakeTestFixture {
    public $name = 'CategoryPost';
    public $table = 'categories_posts';
    public $fields = array(
        'category_id' => array('type' => 'integer', 'null' => false),
        'post_id' => array('type' => 'integer', 'null' => false),
    );
    public $records = array(
        array('category_id' => 1, 'post_id' => 2),
        array('category_id' => 2, 'post_id' => 3),
    );
}
