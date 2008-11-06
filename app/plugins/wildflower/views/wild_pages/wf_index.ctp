<?php
    echo
    $navigation->create(array(
	        'Publish' => '#Publish', 
	        'Draft' => '#Draft', 
	        'Delete' => '#Delete', 
	    ), array('id' => 'list-toolbar')),
    $tree->generate($pages, array('model' => 'WildPage', 'class' => 'list selectable-list', 'element' => 'admin_page_list_item'));
?>
