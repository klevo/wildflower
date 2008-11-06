<h2>Delete <em>"<?php echo $this->data['Page']['title']; ?>"</em> page?</h2>
<?php 
echo $form->create('Page', array('action' => 'delete'));
    echo $form->hidden('Page.id');
    echo $form->submit('Delete'); ?> or <?php echo $html->link('Back to Pages overview', '/' . Configure::read('Routing.admin') . '/pages'); ?>
<?php
echo $form->end();
?>
