<?php

class RemoveUserIdFromRevisionsTable extends BaseMigration {

	public function up() {
        $this->remove_column('revisions', 'user_id');
        $this->rename_table('revisions', 'wild_revisions');
	}//up()

	public function down() {
	    $this->rename_table('wild_revisions', 'revisions');
        $this->add_column('revisions', 'user_id', 'integer');
	}//down()
}
