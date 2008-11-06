<h2><?php echo $page['WildPage']['title']; ?></h2>
<?php echo $page['WildPage']['content']; ?>

<?php $session->flash(); ?>

<?php
    echo 
    $form->create('Message', array('url' => '/contact/create', 'class' => 'contact-form')),
    "<fieldset>\n",
    "<legend>Contact form</legend>\n",
    $form->input('name'),
    $form->input('email'),
    $form->input('phone'),
    $form->input('subject'),
    $form->input('content', array('type' => 'textarea')),
    $form->submit('Send my message'),
    "</fieldset>\n",
    $form->end();
?>

<?php echo $this->element('edit_this', array('controller' => 'wild_pages', 'id' => $page['WildPage']['id'])) ?>
