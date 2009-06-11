<?php 
class AddOnPostsFieldToWildSidebars extends Ruckusing_BaseMigration {

	function up() {
        $this->add_column('wild_sidebars', 'on_posts', 'integer', array('default' => 0));
	}

	function down() {
        $this->remove_column('wild_sidebars', 'on_posts');
	}
}