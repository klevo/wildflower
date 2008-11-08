<?php

class AddUuidToPosts extends BaseMigration {

	public function up() {
        $this->add_column('posts', 'uuid', 'string', array('lenght' => 40, 'null' => false));
	}//up()

	public function down() {
        $this->remove_column('posts', 'uuid');
	}//down()
}
