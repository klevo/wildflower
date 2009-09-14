<?php

class AddThemeSetting extends Ruckusing_BaseMigration {

    public function up() {
        $query = "INSERT INTO settings SET name = 'theme', value = 'kruger', type = 'select', label = 'Theme', `order` = 14";
        $this->execute($query);
    }//up()

    public function down() {
        $query = "DELETE FROM settings WHERE name = 'theme'";
        $this->execute($query);
    }//down()
}
?>