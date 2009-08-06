<h2 class="section"><?php echo __('Categories', true); ?></h2>

<?php if (empty($categoriesForTree)): ?>
    <p><?php echo __('No categories yet.', true); ?></p>
<?php else: ?>
    <?php echo $tree->generate($categoriesForTree, array('model' => 'Category', 'class' => 'category_list', 'element' => '../categories/_edit_tree_item')); ?>
<?php endif; ?>

<?php $partialLayout->blockStart('sidebar'); ?>
    <li id="add-category-box" class="sidebar-box">
        <h4 class="add"><?php __('Add a new category'); ?></h4>
        <?php
            $createCategoryUrl = $html->url(array('controller' => 'categories', 'action' => 'create', 'base' => false));
            echo
            $form->create('Category', array('url' => $here)),
            $form->input('Category.title', array('between' => '<br />')),
            $form->input('Category.parent_id', array('between' => '<br />', 'options' => $categoriesForSelect, 'empty' => '(none)')),
            $form->end('Add this category');
        ?>
    </li>
<?php $partialLayout->blockEnd(); ?>