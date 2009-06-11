<?php 
class CreateWildMenusAndWildMenuItems extends Ruckusing_BaseMigration {

	function up() {
        $menus = $this->create_table('wild_menus');
        $menus->column('title', 'string');
        $menus->column('slug', 'string');
        $menus->finish();        
        
        $menu_items = $this->create_table('wild_menu_items');
        $menu_items->column('wild_menu_id', 'integer');
        $menu_items->column('label', 'string');
        $menu_items->column('url', 'string');
        $menu_items->column('order', 'integer');
        $menu_items->finish();
	}

	function down() {
        $this->drop_table('wild_menu_items');
        $this->drop_table('wild_menus');
	}
}