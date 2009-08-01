<h2><?php echo $page['Page']['title']; ?></h2>
<?php echo $page['Page']['content']; ?>

<?php $session->flash(); ?>

<?php
    echo 
    $form->create('Message', array('url' => $here, 'class' => 'contact-form')),
    "<fieldset>\n",
    "<legend>Contact form</legend>\n",
    $form->input('name'),
    $form->input('email'),
    $form->input('content', array('type' => 'textarea', 'label' => 'Message')),
    $form->submit('Send my message'),
    "</fieldset>\n",
    $form->end();
?>

<?php echo $this->element('edit_this', array('controller' => 'pages', 'id' => $page['Page']['id'])) ?>
