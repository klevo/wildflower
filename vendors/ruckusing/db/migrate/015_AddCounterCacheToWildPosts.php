<?php
class AddCounterCacheToWildPosts extends Ruckusing_BaseMigration {

	public function up() {
	    $this->rename_table('posts', 'wild_posts');
	    $this->rename_table('comments', 'wild_comments');
	    
	    $this->add_column('wild_posts', 'wild_comment_count', 'integer', array('default' => 0));
	    $this->rename_column('wild_comments', 'post_id', 'wild_post_id');
	}//up()

	public function down() {
	    $this->rename_column('wild_comments', 'wild_post_id', 'post_id');
	    $this->remove_column('wild_posts', 'wild_comment_count');
	    
        $this->rename_table('wild_posts', 'posts');
	    $this->rename_table('wild_comments', 'comments');
	}//down()
}
