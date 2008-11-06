<?php 
class CategoryTestFixture extends CakeTestFixture {
    public $name = 'CategoryTest';
    public $fields = array(
        'id' => array('type' => 'integer', 'extra' => 'auto_increment', 'key' => 'primary'),
        'parent_id' => array('type' => 'integer', 'null' => true),
        'lft' => array('type' => 'integer', 'null' => true),
        'rght' => array('type' => 'integer', 'null' => true),
        'slug' => array('type' => 'string', 'null' => false),
        'title' => array('type' => 'string', 'null' => false),
        'description' => array('type' => 'string', 'null' => true)
    );
    public $records = array(
        array('id' => 1, 'parent_id' => null, 'lft' => 1, 'rght' => 2, 'slug' => 'cakephp', 'title' => 'CakePHP', 'description' => ''),
        array('id' => 2, 'parent_id' => null, 'lft' => 3, 'rght' => 4, 'slug' => 'unit-testing', 'title' => 'Unit testing', 'description' => ''),
    );
}
