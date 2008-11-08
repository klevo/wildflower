<div class="sitemap">
    <h2><?php echo __('Add new node') ?></h2>

<?php echo $form->create('Sitemap');?>
	<fieldset>
	<?php
		echo $form->input('parent_id', array('label' => __('Parent Id', true), 'type' => 'select', 'options' => $parentNodes));
		echo $form->input('loc', array('label' => __('Loc', true)));
		echo $form->input('changefreq', array('label' => __('Change freq', true), 'type' => 'select', 'options' => array('', 'always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never')));
		echo $form->input('priority', array('label' => __('Priority', true)));
	?>
	</fieldset>
<?php echo $form->end(__('Submit', true));?>
</div>
