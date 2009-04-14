<?php 
class AddCustomFieldsFieldToPages extends Ruckusing_BaseMigration {

	function up() {
        $this->add_column('wild_pages', 'custom_fields', 'text');
	}

	function down() {
        $this->remove_column('wild_pages', 'custom_fields');
	}
}