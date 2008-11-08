<?php if (!empty($categories)) { ?>
	<div class="multiple-checkbox">
	<?php
	   echo $form->label('Category.Category', __('Categories', true)),
	       $habtm->checkboxMultiple('Category.Category', $categories, $selectedCategories);
	?>
    </div>
<?php } ?>