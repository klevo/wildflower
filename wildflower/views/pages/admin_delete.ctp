<h2><?php echo __('Delete') ?> <em>"<?php echo $this->data['Page']['title']; ?>"</em> <?php echo __('page') ?> ?</h2>
<?php 
echo $form->create('Page', array('action' => 'delete'));
    echo $form->hidden('Page.id');
    echo $form->submit(__('Delete', true)); 
    echo ' ' . __('or') . ' ';
    echo $html->link(__('Back to Pages overview', true), '/' . Configure::read('Routing.admin') . '/pages') ; 
echo $form->end();
?>
