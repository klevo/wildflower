<h2 class="section"><?php __('Create a new menu'); ?></h2>

<?php
    echo
    $form->create('Menu', array('url' => $here, 'class' => 'generic_form')),
    $form->input('title', array('after' => $html->tag('p', __('(does not show anywhere, it\'s for your reference)', true))));
?>

<h3>Menu items</h3>
<ul class="menu_items">
    <li>
        <?php echo $form->input('MenuItem.0.label', array('div' => array('class' => 'menu_item_label'))); ?>
        <?php echo $form->input('MenuItem.0.url', array('div' => array('class' => 'menu_item_url'))); ?>
        <div><?php echo $html->link(__('Remove', true), '#RemoveMenuItem', array('class' => 'delete')); ?></div>
    </li>
</ul>
    
<p class="add_menu_item_p"><?php echo $html->link(__('Add item', true), '#AddMenuItem', array('id' => 'add_menu_item')); ?></p>


<?php echo $form->end('Create this menu'); ?>

<div class="cancel-edit"> <?php __('or'); ?> <?php echo $html->link(__('Cancel', true), array('action' => 'index', 'controller' => 'sidebars')); ?></div>

<?php $partialLayout->blockStart('sidebar'); ?>
 
<?php $partialLayout->blockEnd(); ?>
