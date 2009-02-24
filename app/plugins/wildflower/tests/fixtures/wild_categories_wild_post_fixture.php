<?php 
class WildCategoriesWildPostFixture extends CakeTestFixture {
    public $name = 'WildCategoriesWildPost';
    public $fields = array(
        'wild_category_id' => array('type' => 'integer', 'null' => false),
        'wild_post_id' => array('type' => 'integer', 'null' => false),
    );
    public $records = array(
        array('wild_category_id' => 1, 'wild_post_id' => 2),
        array('wild_category_id' => 2, 'wild_post_id' => 3),
    );
}
