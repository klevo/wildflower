<div class="user">
<h2><?php echo __('View User') ?></h2>

	<dl>
		<dt><?php echo __('Id') ?></dt>
		<dd>&nbsp;<?php echo $user['User']['id']?></dd>
		<dt><?php echo __('Login') ?></dt>
		<dd>&nbsp;<?php echo $user['User']['login']?></dd>
		<dt><?php echo __('Password') ?></dt>
		<dd>&nbsp;<?php echo $user['User']['password']?></dd>
		<dt><?php echo __('Email') ?></dt>
		<dd>&nbsp;<?php echo $user['User']['email']?></dd>
		<dt><?php echo __('Name') ?></dt>
		<dd>&nbsp;<?php echo $user['User']['name']?></dd>
	</dl>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Edit User', true),   array('action'=>'edit', $user['User']['id'])); ?> </li>
		<li><?php echo $html->link(__('Delete User', true), array('action'=>'delete', $user['User']['id']), null, __('Are you sure you want to delete', true) . ' #' . $user['User']['id'] . '?'); ?> </li>
		<li><?php echo $html->link(__('List Users', true), array('action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New User', true), array('action'=>'add')); ?> </li>
	</ul>

</div>
