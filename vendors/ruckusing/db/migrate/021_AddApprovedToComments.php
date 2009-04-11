<?php 
class AddApprovedToComments extends Ruckusing_BaseMigration {

	function up() {
        $this->add_column('wild_comments', 'approved', 'integer', array('default' => 1));
        $this->query("INSERT INTO wild_settings (name, value, description, type, label, `order`) VALUES ('approve_comments', 1, '', 'checkbox', 'Approve each comment before publishing it', 12)");
	}

	function down() {
        $this->remove_column('wild_comments', 'approved');
        $this->query("DELETE FROM wild_settings WHERE name = 'approve_comments'");
	}
}