<?php

class AddDraftColumnToPostsTable extends Ruckusing_BaseMigration {

	public function up() {
        $this->add_column("posts", "draft", "integer", array('default' => 0, 'limit' => 1, 'null' => false));
	}//up()

	public function down() {
        $this->remove_column("posts", "draft");
	}//down()
}
