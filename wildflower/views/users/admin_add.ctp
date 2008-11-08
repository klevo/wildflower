<h2 class="top"><?php echo __('New User') ?></h2>

<div class="standard-form">
	<?php echo $form->create('User');?>
  <?php echo $form->input('name', array('label' => __('Name', true), 'tabindex' => '1')); ?>
  <?php echo $form->input('email', array('label' => __('Email', true), 'class' => 'required', 'tabindex' => '2'));?>
	<?php echo $form->input('login', array('label' => __('Login', true), 'class' => 'required', 'tabindex' => '3')); ?>
	<?php echo $form->input('password', array('label' => __('Password', true), 'class' => 'required', 'tabindex' => '4')); ?>
	<?php echo $form->input('confirm_password', array('label' => __('Confirm password', true), 'type' => 'password', 'class' => 'required', 'tabindex' => '5')); ?>
		
		<div class="submit form-bottom">
            <input type="submit" value="Add" tabindex="6">
            <span class="edit-actions"> <?php echo __('Password') ?>  
            <?php echo $html->link(__('Cancel', true), array('action' => 'index'), array('tabindex' => '7', 'class' => 'cancel')); ?>
            </span>
    </div>
	</form>
</div>
