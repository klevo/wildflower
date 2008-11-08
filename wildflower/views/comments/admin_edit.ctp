<h2 class="top">Editing comment #<?php echo $this->data['Comment']['id'] ?></h2>

<?php
    if ($session->check('Message.flash')) {
        $session->flash();
    }

    $postUrl = '/' . WILDFLOWER_POSTS_INDEX . '/' . $this->data['Post']['slug'];
    echo $form->create('Comment', array('action' => 'update')),
         $form->hidden('Post.slug'), 
         $form->hidden('Post.id'), 
         $form->hidden('Post.permalink', array('value' => $html->url($postUrl, true))), 
         $form->hidden('id'), 
         $form->input('name', array('label'=>__('Name', true))), 
         $form->input('email', array('label'=>__('Email', true))), 
         $form->input('url', array('label'=>__('Url', true))), 
         $form->input('content', array('label'=>__('Content', true))), 
         
         $form->input('spam', array('type' => 'checkbox', 'label'=>__('Spam', true))),
         
         $form->end(__('Save changes', true)); 
?>

<div id="sidebar">
    <?php echo $html->link(__('All comments', true), array('action' => 'index')) ?>
    <?php echo $html->link(__('Comments marked as spam', true), array('action' => 'spam')) ?>
    <?php echo $html->link(__('Delete this comment', true), array('action' => 'delete_confirmation', 'id' => $this->data['Comment']['id'])) ?>
</div>