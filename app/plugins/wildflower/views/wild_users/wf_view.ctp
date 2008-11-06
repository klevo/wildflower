<div class="user">
<h2>View User</h2>

	<dl>
		<dt>Id</dt>
		<dd>&nbsp;<?php echo $user['User']['id']?></dd>
		<dt>Login</dt>
		<dd>&nbsp;<?php echo $user['User']['login']?></dd>
		<dt>Password</dt>
		<dd>&nbsp;<?php echo $user['User']['password']?></dd>
		<dt>Email</dt>
		<dd>&nbsp;<?php echo $user['User']['email']?></dd>
		<dt>Name</dt>
		<dd>&nbsp;<?php echo $user['User']['name']?></dd>
	</dl>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link('Edit User',   array('action'=>'edit', $user['User']['id'])); ?> </li>
		<li><?php echo $html->link('Delete User', array('action'=>'delete', $user['User']['id']), null, 'Are you sure you want to delete #' . $user['User']['id'] . '?'); ?> </li>
		<li><?php echo $html->link('List Users', array('action'=>'index')); ?> </li>
		<li><?php echo $html->link('New User', array('action'=>'add')); ?> </li>
	</ul>

</div>
