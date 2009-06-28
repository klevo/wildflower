<h2 class="section"><?php echo __('Categories', true); ?></h2>

<?php if (empty($categoriesForTree)): ?>
    <p><?php echo __('No categories yet.', true); ?></p>
<?php else: ?>
    <?php echo $tree->generate($categoriesForTree, array('model' => 'WildCategory', 'class' => 'category_list', 'element' => '../wild_categories/_edit_tree_item')); ?>
<?php endif; ?>

<?php $partialLayout->blockStart('sidebar'); ?>
    <li id="add-category-box" class="sidebar-box">
        <h4 class="add"><?php __('Add a new category'); ?></h4>
        <?php
            $createCategoryUrl = $html->url(array('controller' => 'wild_categories', 'action' => 'create', 'base' => false));
            echo
            $form->create('WildCategory', array('url' => $here)),
            $form->input('WildCategory.title', array('between' => '<br />')),
            $form->input('WildCategory.parent_id', array('between' => '<br />', 'options' => $categoriesForSelect, 'empty' => '(none)')),
            $form->end('Add this category');
        ?>
    </li>
<?php $partialLayout->blockEnd(); ?>