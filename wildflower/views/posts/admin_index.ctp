<?php
    echo $navigation->create(array(
	        'Publish' => '#Publish', 
	        'Draft' => '#Draft', 
	        'Delete' => '#Delete', 
	    ), array('id' => 'list-toolbar'));
    
    // The list node
    function listItemCallback($node, $html) {
        $editLink = $html->link($node['Post']['title'], 
           array('action' => 'edit', 'id' => $node['Post']['id']),
           array('title' => 'Edit'));
        $actions = '<span class="actions">'
            . $html->link('View', '/' . WILDFLOWER_POSTS_INDEX . "/{$node['Post']['slug']}")
            . '</span>';
        
        $draftStatus = '';
        if ($node['Post']['draft']) {
            $draftStatus = '<small class="draft-status">' . __('Draft', true) . '</small>';
        }

		$posted = date('j. M y', strtotime($node['Post']['created']));
        
        return '<div class="list-item"><small class="post-date">' . $posted . '</small> ' . $editLink . $draftStatus . '</div>';
    }

    echo $list->create($posts, array('model' => 'Post', 'class' => 'list selectable-list'));
?>

<div class="paginator">
	<?php echo $paginator->counter(array(
		'format' => __('%start% to %end% of %count%', true),
		'format' => sprintf(__('Add %s', true), __('Sitemap', true))
	)); ?>
</div>
    
