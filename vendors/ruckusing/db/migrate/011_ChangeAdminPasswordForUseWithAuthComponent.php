<?php
class ChangeAdminPasswordForUseWithAuthComponent extends Ruckusing_BaseMigration {

	public function up() {
        $this->query("UPDATE users SET password = 'e569c558b6119c2948d97ff3bffd87423f75c2b1' WHERE login = 'admin'");
	}//up()

	public function down() {
        $this->query("UPDATE users SET password = '24c05ce1409afb5dad4c5bddeb924a4bc3ea00f5' WHERE login = 'admin'");
	}//down()
}
