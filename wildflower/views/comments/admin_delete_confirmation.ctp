<h2 class="top"><?php echo __('Do you really want to delete comment').'#'.$this->data['Comment']['id'] ?> ?</h2>

<ul>
    <li><?php echo __('Author') . ' :' . $this->data['Comment']['name'] ?></li>
    <li><?php echo __('Email') . ' :' . $this->data['Comment']['email'] ?></li>
    <li><?php echo __('Web') . ' :' . $this->data['Comment']['url'] ?></li>
    <li><?php echo __('Comment text') . ' :' . $this->data['Comment']['content'] ?></li>
</ul>

<?php
    echo $form->create('Comment', array('action' => 'delete')),
         $form->hidden('id'), 
         $form->end(__('Delete this comment', true)),
         $html->link(__('Cancel', true), $referer, array('class' => 'cancel'));
?>

<div id="sidebar">
    <?php echo $html->link(__('All comments', true), array('action' => 'index')) ?>
    <?php echo $html->link(__('Comments marked as spam', true), array('action' => 'spam')) ?>
</div>