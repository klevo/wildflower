<?php 
class CreateWidgetsTable extends Ruckusing_BaseMigration {

	function up() {
        $widgets = $this->create_table('wild_widgets');
        $widgets->column('config', 'text');
        $widgets->finish();
	}

	function down() {
        $this->drop_table('wild_widgets');
	}
}