<h2 class="top">Add a new category</h2>

<?php 
echo $form->create('Category');
    if (!empty($parentCategories)) {
    	echo $form->label('Category.parent_id', __('Parent category', true));
        echo $form->select('Category.parent_id', $parentCategories);
    }
    echo $form->input('Category.title', array('label'=>__('Title', true)));
    echo $form->input('Category.description', array('label'=>__('Description', true)));
    echo $form->submit(__('Add', true));
echo $form->end();
?>