<?php 
class WildCategoryWildPostFixture extends CakeTestFixture {
    public $name = 'WildCategoryWildPost';
    public $table = 'wild_categories_wild_posts';
    public $fields = array(
        'wild_category_id' => array('type' => 'integer', 'null' => false),
        'wild_post_id' => array('type' => 'integer', 'null' => false),
    );
    public $records = array(
        array('wild_category_id' => 1, 'wild_post_id' => 2),
        array('wild_category_id' => 2, 'wild_post_id' => 3),
    );
}
