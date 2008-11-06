<h2>Delete user <em>"<?php echo $this->data['User']['name']; ?>"</em>?</h2>
<?php 
echo $form->create('User', array('action' => 'delete'));
    echo $form->hidden('id');
    echo $form->submit('Delete'); ?> or <?php echo $html->link('Back to Users overview', '/' . Configure::read('Routing.admin') . '/users'); ?>
<?php
echo $form->end();
?>
