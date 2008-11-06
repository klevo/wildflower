<h2 class="top">New User</h2>

<div class="standard-form">
	<?php echo $form->create('User');?>
        <?php echo $form->input('name', array('tabindex' => '1')); ?>
        <?php echo $form->input('email', array('class' => 'required', 'tabindex' => '2'));?>
		<?php echo $form->input('login', array('class' => 'required', 'tabindex' => '3')); ?>
		<?php echo $form->input('password', array('class' => 'required', 'tabindex' => '4')); ?>
		<?php echo $form->input('confirm_password', array('type' => 'password', 'class' => 'required', 'tabindex' => '5')); ?>
		
		<div class="submit form-bottom">
            <input type="submit" value="Add" tabindex="6">
            <span class="edit-actions"> or 
            <?php echo $html->link('Cancel', array('action' => 'index'), array('tabindex' => '7', 'class' => 'cancel')); ?>
            </span>
        </div>
	</form>
</div>
