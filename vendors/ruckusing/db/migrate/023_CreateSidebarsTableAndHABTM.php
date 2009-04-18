<?php 
class CreateSidebarsTableAndHABTM extends Ruckusing_BaseMigration {

	function up() {
        $sidebars = $this->create_table('wild_sidebars');
        $sidebars->column('title', 'string');
        $sidebars->column('content', 'text');
        $sidebars->column('created', 'datetime');
        $sidebars->column('updated', 'datetime');
        $sidebars->finish();
        
        $sidebars = $this->create_table('wild_pages_wild_sidebars');
        $sidebars->column('wild_page_id', 'integer');
        $sidebars->column('wild_sidebar_id', 'integer');
        $sidebars->finish();
	}

	function down() {
        $this->drop_table('wild_sidebars');
        $this->drop_table('wild_pages_wild_sidebars');
	}
}