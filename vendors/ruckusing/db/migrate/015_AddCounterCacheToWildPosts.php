<?php
class AddCounterCacheToWildPosts extends BaseMigration {

	public function up() {
	    $this->rename_table('posts', 'wild_posts');
	    $this->rename_table('comments', 'wild_comments');
	    
	    $this->add_column('wild_posts', 'wild_comment_count', 'integer', array('default' => 0));
	}//up()

	public function down() {
	    $this->remove_column('wild_posts', 'wild_comment_count');
	    
        $this->rename_table('wild_posts', 'posts');
	    $this->rename_table('wild_comments', 'comments');
	}//down()
}
