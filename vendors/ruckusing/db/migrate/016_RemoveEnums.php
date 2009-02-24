<?php

class RemoveEnums extends Ruckusing_BaseMigration {

	public function up() {
        $this->change_column('settings', 'type', 'string');
	}//up()

	public function down() {
        // No downgrade needed
	}//down()
}
?>