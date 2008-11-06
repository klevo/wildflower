<?php

class AddSidebarContentColumnToPages extends BaseMigration {

	public function up() {
        $this->add_column('pages', 'sidebar_content', 'text');
	}//up()

	public function down() {
        $this->remove_column('pages', 'sidebar_content');
	}//down()
}
?>