<?php
echo $navigation->create(array(
        'Show All' => array('action' => 'index')
    ), array('id' => 'sub-nav', 'class' => 'always-current'));
?>

<div class="box">
    <h3><?php echo __('Change password for user') . ' ' . $this->data['User']['name']; ?></h3>
    <?php echo 
        $form->create('User', array('action' => 'update_password')),
        $form->input('password', array('between' => '<br />', 'label' => __('New password', true), 'tabindex' => '1')),
        $form->input('confirm_password', array('between' => '<br />', 'label' => __('New password again', true), 'type' => 'password', 'tabindex' => '2')),
        $form->hidden('name'),
        $form->hidden('id'),
        $form->end(__('Save', true));
    ?>
</div>
