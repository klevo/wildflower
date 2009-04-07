<?php 
    echo 
    $form->create('WildPost', array('url' => $html->url(array('action' => 'update', 'base' => false)))),
    '<div>',
    $form->hidden('id'),
    $form->hidden('draft'),
    $form->hidden('categories_can_be_empty'), // Helps to determine whether the user deselected all the categories
    '</div>';
?>

<h2 class="section"><?php __('Check categories to post under'); ?></h2>

<?php echo $tree->generate($categoriesForTree, array('model' => 'WildCategory', 'class' => 'category-list checkbox-list', 'element' => '../wild_categories/list_item', 'inCategories' => $inCategories)); ?>

<div id="edit-buttons">
<div class="submit save-section">
    <input type="submit" value="<?php __('Save categories'); ?>" />
</div>
<div class="cancel-edit"> <?php __('or'); ?> <?php echo $html->link(__('Cancel and go back to post edit', true), array('action' => 'edit', $this->data['WildPost']['id'])); ?></div>
</div>

<?php echo $form->end(); ?>


<?php $partialLayout->blockStart('sidebar'); ?>
    <li class="sidebar-box">
        <h4><?php __('Categorizing post...'); ?></h4>
        <?php echo $html->link($this->data['WildPost']['title'], array('action' => 'edit', $this->data['WildPost']['id']), array('class' => 'edited-item-link')); ?>
    </li>
    <li id="add-category-box" class="sidebar-box">
        <h4 class="add"><?php __('Add a new post category'); ?></h4>
        <?php
            $createCategoryUrl = $html->url(array('controller' => 'wild_categories', 'action' => 'create', 'base' => false));
            echo
            $form->create('WildCategory', array('url' => $createCategoryUrl)),
            $form->input('WildCategory.title', array('between' => '<br />')),
            $form->input('WildCategory.parent_id', array('between' => '<br />', 'options' => $categories, 'empty' => '(none)')),
            '<div>',
            $form->hidden('WildCategory.wild_post_id', array('value' => $this->data['WildPost']['id'])),
            '</div>',
            $form->end('Add this category');
        ?>
    </li>
<?php $partialLayout->blockEnd(); ?>
