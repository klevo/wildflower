<?php
    $postUrl = Post::getUrl($this->data['Post']['uuid']);
    
    echo 
    $navigation->create(array(
		'View' => $postUrl . "#comment-{$this->data['Comment']['id']}",
		'Delete' => array('action' => 'admin_delete', $this->data['Comment']['id']),
        'Approved' => array('action' => 'admin_index'),
        'Spam' => array('action' => 'admin_spam'),
    ), array('id' => 'sub-nav'));
    
    $session->flash();
    
    echo 
    $form->create('Comment', array('url' => $html->url(array('action' => 'admin_edit', 'base' => false)))),
    $form->hidden('Post.slug'), 
    $form->hidden('Post.id'), 
    $form->hidden('Post.permalink', array('value' => $html->url($postUrl, true))), 
    $form->hidden('id'), 
    $form->input('name', array('between' => '<br />')), 
    $form->input('email', array('between' => '<br />')), 
    $form->input('url', array('between' => '<br />')), 
    $form->input('content', array('between' => '<br />')), 
    $form->input('spam', array('type' => 'checkbox', 'label' => 'This is a spam')),
    $wild->submit('Save'),
    $form->end();
?>
