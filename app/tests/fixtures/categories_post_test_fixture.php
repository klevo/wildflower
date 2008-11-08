<?php 
class CategoriesPostTestFixture extends CakeTestFixture {
    public $name = 'CategoriesPostTest';
    public $fields = array(
        'category_test_id' => array('type' => 'integer', 'null' => false),
        'post_test_id' => array('type' => 'integer', 'null' => false)
    );
    public $records = array(
        array('category_test_id' => 1, 'post_test_id' => 2),
        array('category_test_id' => 2, 'post_test_id' => 3),
    );
}
