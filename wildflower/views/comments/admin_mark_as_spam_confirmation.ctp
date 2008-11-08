<h2 class="top"><?php echo sprintf(__('Do you want to mark comment #%s as spam',true),$this->data['Comment']['id']); ?></h2>
<ul>
    <li><?php echo __('Author') . ' :' . $this->data['Comment']['name'] ?></li>
    <li><?php echo __('Email') . ' :' . $this->data['Comment']['email'] ?></li>
    <li><?php echo __('Web') . ' :' . $this->data['Comment']['url'] ?></li>
    <li><?php echo __('Comment text') . ' :<br />' . $this->data['Comment']['content'] ?></li>
</ul>

<?php
    echo $form->create('Comment', array('action' => 'mark_as_spam')),
         $form->hidden('id'), 
         $form->end(__('Mark as spam', true)),
         $html->link(__('Cancel', true), $referer, array('class' => 'cancel'));
?>

<div id="sidebar">
    <?php echo $html->link(__('All comments', true), array('action' => 'index')) ?>
    <?php echo $html->link(__('Comments marked as spam', true), array('action' => 'spam')) ?>
</div>