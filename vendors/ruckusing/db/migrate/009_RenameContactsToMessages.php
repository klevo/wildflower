<?php

class RenameContactsToMessages extends BaseMigration {

	public function up() {
        $this->rename_table('contacts', 'messages');
        $this->add_column('messages', 'subject', 'string');
        $this->rename_column('messages', 'message', 'content');
	}//up()

	public function down() {
        $this->rename_table('messages', 'contacts');
        $this->remove_column('contacts', 'subject');
        $this->rename_column('contacts', 'content', 'message');
	}//down()
}
?>