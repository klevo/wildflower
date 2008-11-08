<?php
echo $navigation->create(array(
        'Show All' => array('action' => 'index')
    ), array('id' => 'sub-nav', 'class' => 'always-current'));
?>

<div class="box">
    <h3><?php echo __('Editing user') . ' ' . $this->data['User']['name']; ?></h3>
    <?php echo 
        $form->create('User', array('action' => 'update')),
        '<div class="options-left">',
        $form->input('name', array('label' => __('Name', true), 'between' => '<br />', 'tabindex' => '1')),
        $form->input('email', array('label' => __('Email', true), 'between' => '<br />', 'tabindex' => '2')),
        '</div>',
        '<div class="options-right">',
        $form->input('login', array('label' => __('Login', true), 'between' => '<br />', 'tabindex' => '3')),
        '<p>', $html->link(__('Change password', true), array('action' => 'change_password', $this->data['User']['id'])), '</p>',
        $form->hidden('id'),
        '</div>',
        $form->end(__('Save', true));
    ?>
</div>
