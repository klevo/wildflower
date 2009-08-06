<?php

class RemoveWildPrefix extends Ruckusing_BaseMigration {

	public function up() {
        $this->rename_table('wild_assets', 'assets');
        $this->rename_table('wild_categories', 'categories');
        $this->rename_table('wild_categories_wild_posts', 'categories_posts');
        $this->rename_table('wild_comments', 'comments');
        $this->rename_table('wild_menus', 'menus');
        $this->rename_table('wild_menu_items', 'menu_items');
        $this->rename_table('wild_messages', 'messages');
        $this->rename_table('wild_pages', 'pages');
        $this->rename_table('wild_pages_wild_sidebars', 'pages_sidebars');
        $this->rename_table('wild_posts', 'posts');
        $this->rename_table('wild_revisions', 'revisions');
        $this->rename_table('wild_settings', 'settings');
        $this->rename_table('wild_sidebars', 'sidebars');
        $this->rename_table('wild_users', 'users');
        $this->rename_table('wild_widgets', 'widgets');
	}//up()

	public function down() {

	}//down()
}
?>