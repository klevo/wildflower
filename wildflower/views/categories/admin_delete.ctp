<h2><?php echo __('Delete') ?><em>"<?php echo $this->data['Category']['title']; ?>"</em> <?php echo __('category') ?> ?</h2>
<?php 
echo $form->create('Category', array('action' => 'delete'));
    echo $form->hidden('Category.id');
    echo $form->submit(__('Delete', true)); ?> or <?php echo $html->link(__('Back to Categories overview', true), '/admin/categories'); ?>
<?php
echo $form->end();
?>
