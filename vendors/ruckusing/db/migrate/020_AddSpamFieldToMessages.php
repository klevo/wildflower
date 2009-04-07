<?php 
class AddSpamFieldToMessages extends Ruckusing_BaseMigration {

	function up() {
        $this->add_column('wild_messages', 'spam', 'integer');
	}

	function down() {
        $this->remove_column('wild_messages', 'spam');
	}
}