<?php
    echo 
	$navigation->create(array(
	        'Delete' => '#Delete', 
	    ), array('id' => 'list-toolbar')),
	$tree->generate($categories, array('model' => 'WildCategory', 'class' => 'list selectable-list', 'element' => 'admin_category_list_item'));
?>


<h3 class="add">Create a new category</h3>
<?php
	echo 
    $form->create('WildCategory', array('action' => 'index')),
    $form->input('title', array('between' => '<br />'));
	if (!empty($parentCategories)) {
        echo $form->input('parent_id', array('type' => 'select', 'options' => $parentCategories, 'empty' => '(none)', 'between' => '<br />'));
    }
    echo 
    $form->input('description', array('between' => '<br />', 'type' => 'textbox')),
    $wild->submit('Create category'),
    $form->end();
?>

