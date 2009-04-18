<h2 class="section"><?php __('Editing sidebar'); ?></h2>

<?php
    echo
    $form->create('WildSidebar', array('url' => $here, 'class' => 'generic_form')),
    $form->hidden('id'),
    $form->input('title'),
    $form->input('content', array('class' => 'tinymce')),
    
    '<h4>Select pages where the sidebar would appear</h4>',
    
    $tree->generate($pages, array('model' => 'WildPage', 'class' => 'category-list checkbox-list', 'element' => '../wild_sidebars/_tree_item')),
    
    $form->end('Save changes');
?>

<div class="cancel-edit"> <?php __('or'); ?> <?php echo $html->link(__('Cancel', true), array('action' => 'index')); ?></div>

<?php $partialLayout->blockStart('sidebar'); ?>
    <li><?php echo $html->link(
        '<span>' . __('Add a new sidebar', true) . '</span>',
        array('action' => 'add'),
        array('class' => 'add', 'escape' => false)); ?>
    </li>
<?php $partialLayout->blockEnd(); ?>

