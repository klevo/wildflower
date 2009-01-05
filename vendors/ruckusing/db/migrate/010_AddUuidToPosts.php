<?php
class AddUuidToPosts extends Ruckusing_BaseMigration {

	public function up() {
        $this->add_column('posts', 'uuid', 'string', array('length' => 40, 'null' => false));
	}//up()

	public function down() {
        $this->remove_column('posts', 'uuid');
	}//down()
}
