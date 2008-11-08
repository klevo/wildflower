<?php if ($isLogged) { ?>
<p><?php echo __("You are already logged in. There's no need to do it again") . '. ' . $html->link('Go to admin area', '/' . Configure::read('Routing.admin')); ?>.</p>
<?php } ?>
<?php
echo $form->create('User', array('url' => '/login'));
echo $form->input('login', array('label' => __('login', true), 'between' => '<br />'));
echo $form->input('password',  array('label' => __('password', true), 'type' => 'password', 'between' => '<br />'));
?>
<div id="remember"><?php echo $form->input('remember', array(
	'type' => 'checkbox',
	'label' => __('Remember me?', true))); ?></div>
<?php
echo $form->submit(__('Log in', true));
echo $form->end();
?>
<p id="gohome"><?php echo $html->link(__('Back to', true) ." $siteName", '/'); ?></p>
