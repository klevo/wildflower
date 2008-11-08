<h2><?php echo $page['Page']['title']; ?></h2>
<?php echo $page['Page']['content']; ?>

<?php $session->flash(); ?>

<?php
    echo $form->create('Message', array('url' => '/contact/create', 'class' => 'contact-form'));
    echo "<fieldset>\n";
    echo "<legend>" . __('Contact form') . "</legend>\n";
    echo $form->input('name', array('label' => __('Name', true)));
    echo $form->input('email', array('label' => __('Email', true)));
    echo $form->input('phone', array('label' => __('Phone', true)));
    echo $form->input('subject', array('label' => __('Subject', true)));
    echo $form->input('content', array('type' => 'textarea', 'label' => __('Content', true)));
    echo $form->submit(__('Send my message', true));
    echo "</fieldset>\n";
    echo $form->end();
?>

<?php echo $this->element('edit_this', array('id' => $page['Page']['id'], 'controller' => 'pages')) ?>
