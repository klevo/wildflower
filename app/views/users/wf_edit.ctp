<?php
echo $navigation->create(array(
        'All users' => array('action' => 'index'),
        'Change password' => array('action' => 'admin_change_password', $this->data['User']['id']),
    ), array('id' => 'sub-nav', 'class' => 'always-current'));
?>


<h3>Editing user <?php echo $this->data['User']['name']; ?></h3>
<?php 
    echo 
    $form->create('User', array('url' => $html->url(array('action' => 'admin_update', 'base' => false)))),
    $form->input('name', array('between' => '<br />', 'tabindex' => '1')),
    $form->input('email', array('between' => '<br />', 'tabindex' => '2')),
    $form->input('login', array('between' => '<br />', 'tabindex' => '3')),
    '<div class="hidden">',
    $form->hidden('id'),
    '</div>',
    $wild->submit('Save changes'),
    $form->end();
?>

