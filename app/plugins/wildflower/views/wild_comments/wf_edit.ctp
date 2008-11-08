<?php
    $postUrl = WildPost::getUrl($this->data['WildPost']['uuid']);
    
    echo 
    $navigation->create(array(
		'View' => $postUrl . "#comment-{$this->data['WildComment']['id']}",
		'Delete' => array('action' => 'wf_delete', $this->data['WildComment']['id']),
        'Approved' => array('action' => 'wf_index'),
        'Spam' => array('action' => 'wf_spam'),
    ), array('id' => 'sub-nav'));
    
    $session->flash();
    
    echo 
    $form->create('WildComment', array('url' => $html->url(array('action' => 'wf_edit', 'base' => false)))),
    $form->hidden('WildPost.slug'), 
    $form->hidden('WildPost.id'), 
    $form->hidden('WildPost.permalink', array('value' => $html->url($postUrl, true))), 
    $form->hidden('id'), 
    $form->input('name', array('between' => '<br />')), 
    $form->input('email', array('between' => '<br />')), 
    $form->input('url', array('between' => '<br />')), 
    $form->input('content', array('between' => '<br />')), 
    $form->input('spam', array('type' => 'checkbox', 'label' => 'This is a spam')),
    $wild->submit('Save'),
    $form->end();
?>
