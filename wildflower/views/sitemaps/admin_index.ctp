<div class="sitemaps">
<h2><?php __('Sitemaps');?></h2>
<p>
<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort('id');?></th>
	<th><?php echo $paginator->sort('lft');?></th>
	<th><?php echo $paginator->sort('rght');?></th>
	<th><?php echo $paginator->sort('parent_id');?></th>
	<th><?php echo $paginator->sort('loc');?></th>
	<th><?php echo $paginator->sort('lastmod');?></th>
	<th><?php echo $paginator->sort('changefreq');?></th>
	<th><?php echo $paginator->sort('priority');?></th>
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($sitemaps as $sitemap):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $sitemap['Sitemap']['id'] ?>
		</td>
		<td>
			<?php echo $sitemap['Sitemap']['lft'] ?>
		</td>
		<td>
			<?php echo $sitemap['Sitemap']['rght'] ?>
		</td>
		<td>
			<?php echo $sitemap['Sitemap']['parent_id'] ?>
		</td>
		<td>
			<?php echo $sitemap['Sitemap']['loc'] ?>
		</td>
		<td>
			<?php echo $sitemap['Sitemap']['lastmod'] ?>
		</td>
		<td>
			<?php echo $sitemap['Sitemap']['changefreq'] ?>
		</td>
		<td>
			<?php echo $sitemap['Sitemap']['priority'] ?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('View', true), array('action'=>'view', $sitemap['Sitemap']['id'])); ?>
			<?php echo $html->link(__('Edit', true), array('action'=>'edit', $sitemap['Sitemap']['id'])); ?>
			<?php echo $html->link(__('Delete', true), array('action'=>'delete', $sitemap['Sitemap']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $sitemap['Sitemap']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
</div>
<div class="paging">
	<?php echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
 | 	<?php echo $paginator->numbers();?>
	<?php echo $paginator->next(__('next', true).' >>', array(), null, array('class'=>'disabled'));?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(sprintf(__('New %s', true), __('Sitemap', true)), array('action'=>'add')); ?></li>
	</ul>
</div>
