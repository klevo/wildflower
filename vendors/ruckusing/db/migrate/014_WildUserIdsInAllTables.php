<?php
class WildUserIdsInAllTables extends Ruckusing_BaseMigration {

	public function up() {
        $this->rename_column('pages', 'user_id', 'wild_user_id');
        $this->rename_column('posts', 'user_id', 'wild_user_id');
	}//up()

	public function down() {
        $this->rename_column('pages', 'wild_user_id', 'user_id');
        $this->rename_column('posts', 'wild_user_id', 'user_id');
	}//down()
}
