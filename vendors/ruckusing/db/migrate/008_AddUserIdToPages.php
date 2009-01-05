<?php

class AddUserIdToPages extends Ruckusing_BaseMigration {

	public function up() {
        $this->add_column('pages', 'user_id', 'integer');
	}//up()

	public function down() {
        $this->remove_column('pages', 'user_id');
	}//down()
}
?>