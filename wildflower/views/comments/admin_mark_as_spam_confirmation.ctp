<h2 class="top">Do you want to mark comment #<?php echo $this->data['Comment']['id'] ?> as spam?</h2>

<ul>
    <li>Author: <?php echo $this->data['Comment']['name'] ?></li>
    <li>Email: <?php echo $this->data['Comment']['email'] ?></li>
    <li>Web: <?php echo $this->data['Comment']['url'] ?></li>
    <li>Comment text:<br /> <?php echo $this->data['Comment']['content'] ?></li>
</ul>

<?php
    echo $form->create('Comment', array('action' => 'mark_as_spam')),
         $form->hidden('id'), 
         $form->end('Mark as spam'),
         $html->link('Cancel', $referer, array('class' => 'cancel'));
?>

<div id="sidebar">
    <?php echo $html->link('All comments', array('action' => 'index')) ?>
    <?php echo $html->link('Comments marked as spam', array('action' => 'spam')) ?>
</div>