<?php 
class RenameRemainingTablesToWild extends Ruckusing_BaseMigration {

	public function up() {
	    $tables = array('messages', 'pages', 'settings', 'categories');
	    foreach ($tables as $table) {
	        $this->rename_table($table, "wild_$table");
	    }
        $this->rename_table('uploads', 'wild_assets');
        $this->rename_table('categories_posts', 'wild_categories_wild_posts');
        $this->rename_column('wild_categories_wild_posts', 'category_id', 'wild_category_id');
        $this->rename_column('wild_categories_wild_posts', 'post_id', 'wild_post_id');
	}

	public function down() {
        $tables = array('messages', 'pages', 'settings', 'categories');
	    foreach ($tables as $table) {
	        $this->rename_table("wild_$table", $table);
	    }
        $this->rename_table('wild_assets', 'uploads');
        $this->rename_column('wild_categories_wild_posts', 'wild_category_id', 'category_id');
        $this->rename_column('wild_categories_wild_posts', 'wild_post_id', 'post_id');
        $this->rename_table('wild_categories_wild_posts', 'categories_posts');
	}
	
}