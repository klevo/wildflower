<?php
class RefactorUsersTable extends BaseMigration {

	public function up() {
        $this->rename_table('users', 'wild_users');
        $this->rename_column('wild_users', 'cookie', 'cookie_token');
	}//up()

	public function down() {
        $this->rename_table('wild_users', 'users');
        $this->rename_column('users', 'cookie_token', 'cookie');
	}//down()
}
