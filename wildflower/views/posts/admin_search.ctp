<?php
    // The list node
    if (empty($results)) {
        // @TODO ListHelper could handle this
        echo '<p class="search-results">' . __('No posts match the search criteria') . '</p>';
    } else {
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
            
            return '<div class="list-item">' . $editLink . $draftStatus . '</div>';
        }
    
        echo $list->create($results, array('model' => 'Post', 'class' => 'list selectable-list search-results'));
    }
?>