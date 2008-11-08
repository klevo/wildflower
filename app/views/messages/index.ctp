<div class="contact-page-content">
    <h2 class="<?php echo "{$page['Page']['slug']}-title" ?>"><?php echo $page['Page']['title']; ?></h2>
    <?php
        echo '<div class="entry">' , $page['Page']['content'], '</div>';
        
        $session->flash();
        
        if ($session->check('Message.email')) {
            pr($session->read('Message.email'));
        }
        
        echo $form->create('Contact', array('url' => '/contact/create', 'class' => 'comment-form')),
            $form->input('name'),
            $form->input('email'),
            $form->input('subject'),
            $form->input('content', array('type' => 'textarea')),
            $form->submit('Send message'),
            $form->end(),
            $this->element('edit_this', array('id' => $page['Page']['id'], 'controller' => 'pages'));
    ?>
</div>
