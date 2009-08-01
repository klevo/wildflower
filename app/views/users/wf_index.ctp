<?php
	echo 
    $form->create('User', array('action' => 'mass_update'));
?>

<h2 class="section"><?php __('User Accounts'); ?></h2>

<?php echo $this->element('wf_select_actions'); ?>

<ul class="list">
    <?php foreach ($users as $user): ?>
        <li class="post-row actions-handle">
            <span class="row-check"><?php echo $form->checkbox('id.' . $user['User']['id']) ?></span>
            <span class="title-row"><?php echo $html->link($user['User']['name'], array('action' => 'wf_edit', $user['User']['id']), array('title' => __('Edit this user account.', true))) ?></span>
            <span class="cleaner"></span>
        </li>
    <?php endforeach; ?>
</ul>

<?php
    echo
    $this->element('wf_select_actions'), 
	//$this->element('wf_pagination'),
    $form->end();
?>



<?php $partialLayout->blockStart('sidebar'); ?>
    <li class="sidebar-box">
        <h4 class="add"><?php __('Add a new user account'); ?></h4>
        <?php echo 
            $form->create('User', array('action' => 'create')),
            $form->input('name', array('between' => '<br />')),
            $form->input('email', array('between' => '<br />')),
            $form->input('login', array('between' => '<br />')),
            $form->input('password', array('between' => '<br />')),
            $form->input('confirm_password', array('between' => '<br />', 'type' => 'password')),
            $wild->submit('Create this user'),
            $form->end();
        ?>
    </li>
<?php $partialLayout->blockEnd(); ?>
    