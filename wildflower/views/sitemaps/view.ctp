<div class="sitemap">
<h2><?php  __('Sitemap');?></h2>
	<dl>
		<dt class="altrow"><?php __('Id') ?></dt>
		<dd class="altrow">
			<?php echo $sitemap['Sitemap']['id'] ?>
			&nbsp;
		</dd>
		<dt><?php __('Lft') ?></dt>
		<dd>
			<?php echo $sitemap['Sitemap']['lft'] ?>
			&nbsp;
		</dd>
		<dt class="altrow"><?php __('Rght') ?></dt>
		<dd class="altrow">
			<?php echo $sitemap['Sitemap']['rght'] ?>
			&nbsp;
		</dd>
		<dt><?php __('Parent Id') ?></dt>
		<dd>
			<?php echo $sitemap['Sitemap']['parent_id'] ?>
			&nbsp;
		</dd>
		<dt class="altrow"><?php __('Loc') ?></dt>
		<dd class="altrow">
			<?php echo $sitemap['Sitemap']['loc'] ?>
			&nbsp;
		</dd>
		<dt><?php __('Lastmod') ?></dt>
		<dd>
			<?php echo $sitemap['Sitemap']['lastmod'] ?>
			&nbsp;
		</dd>
		<dt class="altrow"><?php __('Changefreq') ?></dt>
		<dd class="altrow">
			<?php echo $sitemap['Sitemap']['changefreq'] ?>
			&nbsp;
		</dd>
		<dt><?php __('Priority') ?></dt>
		<dd>
			<?php echo $sitemap['Sitemap']['priority'] ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(sprintf(__('Edit %s', true), __('Sitemap', true)), array('action'=>'edit', $sitemap['Sitemap']['id'])); ?> </li>
		<li><?php echo $html->link(sprintf(__('Delete %s', true), __('Sitemap', true)), array('action'=>'delete', $sitemap['Sitemap']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $sitemap['Sitemap']['id'])); ?> </li>
		<li><?php echo $html->link(sprintf(__('List %s', true), __('Sitemaps', true)), array('action'=>'index')); ?> </li>
		<li><?php echo $html->link(sprintf(__('New %s', true), __('Sitemap', true)), array('action'=>'add')); ?> </li>
	</ul>
</div>
