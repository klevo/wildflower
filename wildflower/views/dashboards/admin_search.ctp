<h2><?php echo __('Find in pages') ?></h2>

<?php 
    echo $form->create('Dashboard', array('action' => 'search'));
    echo $form->input('query', array('label'=>__('Query', true)));
    echo $form->submit(__('Search', true));
    echo $form->end();
?>

<?php if (isset($results) && !empty($results)) { ?>
<h3><?php echo __('Search results for') . '"' . hsc($this->data['Dashboard']['query']) ?>"</h3>

<ul>
<?php
	foreach ($results as $item) {
		$row = '';
		if (isset($item['Page'])) {
            $row = $html->link($item['Page']['title'], 
                array('controller' => 'pages', 'action' => 'edit', $item['Page']['id']));	
		} else if (isset($item['Post'])) {
            $row = $html->link($item['Post']['title'], 
                array('controller' => 'posts', 'action' => 'edit', $item['Post']['id']));
		} else {
			continue;
		}
		
		echo "<li>$row</li>\n";
	}
?>
</ul>
<?php } ?>