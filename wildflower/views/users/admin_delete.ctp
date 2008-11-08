<h2><?php echo __('Delete user')?> <em>"<?php echo $this->data['User']['name']; ?>"</em>?</h2>
<?php 
echo $form->create('User', array('action' => 'delete'));
    echo $form->hidden('id');
    echo $form->submit(__('Delete', true)) . ' ' . __('or') . ' ' . $html->link(__('Back to Users overview', true), '/' . Configure::read('Routing.admin') . '/users'); ?>
<?php
echo $form->end();
?>
