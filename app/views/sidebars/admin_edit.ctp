<h2 class="section"><?php __('Editing sidebar'); ?></h2>

<?php
    echo
    $form->create('Sidebar', array('url' => $here, 'class' => 'generic_form')),
    $form->hidden('id'),
    $form->input('title'),
    $form->input('content', array('class' => 'tinymce')),
    
    '<h4>Select pages where the sidebar would appear</h4>',
    
    $tree->generate($pages, array('model' => 'Page', 'class' => 'category-list checkbox-list', 'element' => '../sidebars/_tree_item')),
    
    $form->input('on_posts', array('type' => 'checkbox', 'label' => 'Blog & posts'));
    
    // Custom associations
    $models = Configure::read('App.customSidebarAssociations');
    if (!empty($models)) {
        foreach ($models as $model) {
            echo $this->element('../sidebars/_' . Inflector::pluralize(low($model)) .  '_tree');
        }
    }
    
    echo $form->end('Save changes');
?>

<div class="cancel-edit"> <?php __('or'); ?> <?php echo $html->link(__('Cancel', true), array('action' => 'index')); ?></div>

<?php $partialLayout->blockStart('sidebar'); ?>
    <li><?php echo $html->link(
        '<span>' . __('Add a new sidebar', true) . '</span>',
        array('action' => 'add'),
        array('class' => 'add', 'escape' => false)); ?>
    </li>
<?php $partialLayout->blockEnd(); ?>

