<h2>Delete <em>"<?php echo $this->data['Category']['title']; ?>"</em> category?</h2>
<?php 
echo $form->create('Category', array('action' => 'delete'));
    echo $form->hidden('Category.id');
    echo $form->submit('Delete'); ?> or <?php echo $html->link('Back to Categories overview', '/admin/categories'); ?>
<?php
echo $form->end();
?>
