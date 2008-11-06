<h2 class="top">Do you really want to delete comment #<?php echo $this->data['Comment']['id'] ?>?</h2>

<ul>
    <li>Author: <?php echo $this->data['Comment']['name'] ?></li>
    <li>Email: <?php echo $this->data['Comment']['email'] ?></li>
    <li>Web: <?php echo $this->data['Comment']['url'] ?></li>
    <li>Comment text:<br /> <?php echo $this->data['Comment']['content'] ?></li>
</ul>

<?php
    echo $form->create('Comment', array('action' => 'delete')),
         $form->hidden('id'), 
         $form->end('Delete this comment'),
         $html->link('Cancel', $referer, array('class' => 'cancel'));
?>

<div id="sidebar">
    <?php echo $html->link('All comments', array('action' => 'index')) ?>
    <?php echo $html->link('Comments marked as spam', array('action' => 'spam')) ?>
</div>