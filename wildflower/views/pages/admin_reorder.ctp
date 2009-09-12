<h2 class="section"><?php __('Reordering pages'); ?></h2>

<p><?php __('Click on a page title to move the page elsewhere.'); ?></p>

<?php
    echo 
    $form->create('Page', array('url' => $here)),
    $tree->generate($pages, array('class' => 'page_reorder_list', 'element' => '../pages/_reorder_item')),
    $form->end('Save changes');
?>

<?php $partialLayout->blockStart('sidebar'); ?>
    <?php echo $this->element('../pages/_page_edit_right_menu'); ?>
<?php $partialLayout->blockEnd(); ?>