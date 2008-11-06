<?php

class AddEmailDeliverySetting extends BaseMigration {

	public function up() {
        $query = "INSERT INTO settings SET name = 'email_delivery', value = 'php', type = 'select'";
        $this->execute($query);
	}//up()

	public function down() {
        $query = "DELETE FROM settings WHERE name = 'email_delivery'";
        $this->execute($query);
	}//down()
}
?>