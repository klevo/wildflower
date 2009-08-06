<?php

class RemoveWildPrefix extends Ruckusing_BaseMigration {

	public function up() {
	    // Columns
        $this->rename_column('wild_categories_wild_posts', 'wild_category_id', 'category_id');
        $this->rename_column('wild_categories_wild_posts', 'wild_post_id', 'post_id');
        
        $this->rename_column('wild_comments', 'wild_post_id', 'post_id');
        
        $this->rename_column('wild_menu_items', 'wild_menu_id', 'menu_id');
        
        $this->rename_column('wild_pages', 'wild_user_id', 'user_id');
        
        $this->rename_column('wild_pages_wild_sidebars', 'wild_page_id', 'page_id');
        $this->rename_column('wild_pages_wild_sidebars', 'wild_sidebar_id', 'sidebar_id');
        
        $this->rename_column('wild_posts', 'wild_user_id', 'user_id');
        $this->rename_column('wild_posts', 'wild_comment_count', 'comment_count');
        
        
	    // Tables
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
        // To lazy to do this...
	}//down()
}
