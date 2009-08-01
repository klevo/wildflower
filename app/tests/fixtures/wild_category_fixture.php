<?php 
class CategoryFixture extends CakeTestFixture {
    public $name = 'Category';
    public $import = 'Category';
    public $records = array(
        array('id' => 1, 'parent_id' => null, 'lft' => 1, 'rght' => 2, 'slug' => 'cakephp', 'title' => 'CakePHP', 'description' => ''),
        array('id' => 2, 'parent_id' => null, 'lft' => 3, 'rght' => 4, 'slug' => 'unit-testing', 'title' => 'Unit testing', 'description' => ''),
    );
}
