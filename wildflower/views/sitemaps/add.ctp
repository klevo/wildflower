<div class="sitemap">
<?php echo $form->create('Sitemap');?>
	<fieldset>
 		<legend><?php echo sprintf(__('Add %s', true), __('Sitemap', true));?></legend>
	<?php
		echo $form->input('lft', array('label' => __('Left', true)));
		echo $form->input('rght', array('label' => __('Right', true)));
		echo $form->input('parent_id', array('label' => __('Parent Id', true)));
		echo $form->input('loc', array('label' => __('Loc', true)));
		echo $form->input('lastmod', array('label' => __('Last modification', true)));
		echo $form->input('changefreq', array('label' => __('Change freq', true)));
		echo $form->input('priority', array('label' => __('Priority', true)));
	?>
	</fieldset>
<?php echo $form->end(__('Submit', true));?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(sprintf(__('List %s', true), __('Sitemaps', true)), array('action'=>'index'));?></li>
	</ul>
</div>
