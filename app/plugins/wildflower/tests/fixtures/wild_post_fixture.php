<?php 
class WildPostFixture extends CakeTestFixture {
    public $name = 'WildPost';
    public $import = 'Wildflower.WildPost';
    public $records = array(
        array('id' => 1, 'slug' => 'some-nice-test-post', 'title' => 'Some nice test post', 'content' => 'First Article Body', 'wild_user_id' => 1, 'description_meta_tag' => '', 'keywords_meta_tag' => '', 'created' => '2008-03-18 10:39:23', 'updated' => '2008-04-18 10:41:31'),
        array('id' => 2, 'slug' => 'alcohol', 'title' => 'A few beers and vodka', 'content' => 'First Article Body', 'wild_user_id' => 1, 'description_meta_tag' => '', 'keywords_meta_tag' => '', 'created' => '2008-03-18 10:39:23', 'updated' => '2008-04-18 10:41:31'),
        array('id' => 3, 'slug' => 'strings-are-fuuun', 'title' => 'Strings are fúúún!', 'content' => 'First Article Body', 'wild_user_id' => 1, 'description_meta_tag' => '', 'keywords_meta_tag' => '', 'created' => '2008-03-18 10:39:23', 'updated' => '2008-04-18 10:41:31'),
    );
}
