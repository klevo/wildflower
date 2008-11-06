<h2 class="top">Add a new category</h2>

<?php 
echo $form->create('Category');
    if (!empty($parentCategories)) {
    	echo $form->label('Category.parent_id', 'Parent category');
        echo $form->select('Category.parent_id', $parentCategories);
    }
    echo $form->input('Category.title');
    echo $form->input('Category.description');
    echo $form->submit('Add');
echo $form->end();
?>