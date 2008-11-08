<h2><?php echo __('Database connection error') ?></h2>
<p>
	<?php echo __('Wildflower can`t connect to your database. Check your database connection details in <em>app/config/database.php</em>') ?>
</p>
<h3><?php echo __('Current config') ?> :</h3>    
<p><?php pr($database_config) ?></p>