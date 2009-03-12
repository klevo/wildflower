<h2 class="section">Reorder Blog Categories</h2>

<?php echo $tree->generate($categoriesForTree, array('model' => 'WildCategory', 'class' => 'category-list reorder-list', 'element' => '../wild_categories/reorder_list_item')); ?>
