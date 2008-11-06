<?php

class AddOrderColumnToSettings extends BaseMigration {

	public function up() {
        $this->add_column('settings', 'order', 'integer', array('null' => false));
        
        $query = "UPDATE settings SET `order` = 1 WHERE name = 'site_name';"
            . "UPDATE settings SET `order` = 2 WHERE name = 'description';"
            . "UPDATE settings SET `order` = 3 WHERE name = 'home_page_id';"
            . "UPDATE settings SET `order` = 4 WHERE name = 'contact_email';"
            . "UPDATE settings SET `order` = 5 WHERE name = 'email_delivery';"
            . "UPDATE settings SET `order` = 6 WHERE name = 'smtp_server';"
            . "UPDATE settings SET `order` = 7 WHERE name = 'smtp_username';"
            . "UPDATE settings SET `order` = 8 WHERE name = 'smtp_password';"
            . "UPDATE settings SET `order` = 9 WHERE name = 'wordpress_api_key';"
            . "UPDATE settings SET `order` = 10 WHERE name = 'google_analytics_code'";
        
        $queries = explode(';', $query);
        foreach ($queries as $q) {
            $this->execute($q);
        }
	}//up()

	public function down() {
        $this->remove_column('settings', 'order');
	}//down()
}
?>