<?php
    echo 
	$navigation->create(array(
	        'Delete' => '#Delete', 
	    ), array('id' => 'list-toolbar')),
	$tree->generate($categories, array('model' => 'Category', 'class' => 'list selectable-list', 'element' => 'admin_category_list_item'));
?>

<h3 class="add"><?php __('Create a new category') ; ?></h3>
<?php
	echo 
    $form->create('Category', array('action' => 'index')),
    $form->input('title', array('between' => '<br />','label'=>__('Title',true)));
	if (!empty($parentCategories)) {
        echo $form->input('parent_id', array('type' => 'select', 'options' => $parentCategories, 'empty' => '('.__('none',true).')', 'between' => '<br />','label'=>__('Parent category',true)));
    }
    echo 
    $form->input('description', array('between' => '<br />', 'type' => 'textbox','label'=>__('Description',true))),
    $cms->submit(__('Create category',true)),
    $form->end();
?>
