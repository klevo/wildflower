<?php

class AddCacheSetting extends Ruckusing_BaseMigration {
	
    public function up() {
        $query = "INSERT INTO settings SET name = 'cache', value = 'on', type = 'select', label = 'Page and post caching', `order` = 11";
        $this->execute($query);
    }//up()

    public function down() {
        $query = "DELETE FROM settings WHERE name = 'cache'";
        $this->execute($query);
    }//down()
}
?>