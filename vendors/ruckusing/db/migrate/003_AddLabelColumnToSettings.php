<?php

class AddLabelColumnToSettings extends BaseMigration {

	public function up() {
        $this->add_column('settings', 'label', 'string');
        
        $query = "UPDATE settings SET label = 'Home page', description = 'Page that will be shown when visiting the site root.' WHERE name = 'home_page_id'";
        $this->execute($query);
        
        $query = "UPDATE settings SET label = 'Contact email address', name = 'contact_email' WHERE name = 'contact_form_email_to'";
        $this->execute($query);
	}//up()

	public function down() {
        $this->remove_column('settings', 'label');
        
        $query = "UPDATE settings SET description = '' WHERE name = 'home_page_id'";
        $this->execute($query);
        
        $query = "UPDATE settings SET name = 'contact_form_email_to' WHERE name = 'contact_email'";
        $this->execute($query);
	}//down()
}
