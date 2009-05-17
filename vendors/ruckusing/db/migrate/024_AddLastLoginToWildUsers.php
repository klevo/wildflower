<?php 
class AddLastLoginToWildUsers extends Ruckusing_BaseMigration {

	function up() {
        $this->add_column('wild_users', 'last_login', 'timestamp');
	}

	function down() {
        $this->remove_column('wild_users', 'last_login');
	}
}