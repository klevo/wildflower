<h2><?php echo __('Delete') ?> <em>"<?php echo $this->data['Post']['title']; ?>"</em> <?php echo __('page') ?> ?</h2>
<?php 
echo $form->create('Post', array('action' => 'delete'));
    echo $form->hidden('Post.id');
    echo $form->submit(__('Delete', true)); 
    echo ' ' . __('or') . ' ';
    echo $html->link(__('Back to Posts overview', true), '/' . Configure::read('Routing.admin') . '/posts') ; 
<?php
echo $form->end();
?>
