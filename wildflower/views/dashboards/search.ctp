<div id="primary-content">

	<h2><?php echo __('Search') ?></h2>
	
	<?php 
	    echo $form->create('Dashboard', array('action' => 'search'));
	    echo "<fieldset>\n";
	    echo $form->input('query', array('label'=>__('Query', true)));
	    echo $form->submit(__('Search', true));
	    echo "</fieldset>\n";
	    echo $form->end();
	?>
	
	<?php if (isset($results) && !empty($results)) { ?>
	<h3><?php echo __('Search results for') . '"' . hsc($this->data['Dashboard']['query']) ?>"</h3>
	
	<ul>
	<?php
		foreach ($results as $item) {
			$row = '';
	        if (isset($item['Page'])) {
	            $row = $html->link($item['Page']['title'], $item['Page']['url']); 
	        } else if (isset($item['Post'])) {
	            $row = $html->link($item['Post']['title'], "/p/{$item['Post']['slug']}");
	        } else {
	            continue;
	        }
	        
	        echo "<li>$row</li>\n";
		}
	?>
	</ul>
	<?php } ?>
	
</div>

<?php echo $this->renderElement('sidebar') ?>