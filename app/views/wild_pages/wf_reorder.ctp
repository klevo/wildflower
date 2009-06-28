<h2 class="section"><?php __('Reordering pages'); ?></h2>

<p><?php __('Click on a page title to move the page elsewhere.'); ?></p>

<?php
    echo 
    $form->create('WildPage', array('url' => $here)),
    $tree->generate($pages, array('class' => 'page_reorder_list', 'element' => '../wild_pages/_reorder_item')),
    $form->end('Save changes');
?>

<?php $partialLayout->blockStart('sidebar'); ?>
    <?php echo $this->element('../wild_pages/_page_edit_right_menu'); ?>
<?php $partialLayout->blockEnd(); ?>