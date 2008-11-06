<?php

class ChangeSettingsTypeColumn extends BaseMigration {

	public function up() {
        $query = "ALTER TABLE settings MODIFY COLUMN type ENUM('text','textbox','select','checkbox','radio','password') NOT NULL";
        $this->execute($query);
        
        $query = "UPDATE settings SET type = 'text'";
        $this->execute($query);
        
        $query = "UPDATE settings SET type = 'textbox' WHERE name IN('description','google_analytics_code')";
        $this->execute($query);
        
        $query = "UPDATE settings SET type = 'select' WHERE name = 'home_page_id'";
        $this->execute($query);
	}//up()

	public function down() {
        $query = "ALTER TABLE settings MODIFY COLUMN type ENUM('general','theme') NOT NULL DEFAULT 'general'";
        $this->execute($query);
	}//down()
}
