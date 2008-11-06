<?php
    if (empty($results)) {
        echo '<p class="search-results">No pages match the search criteria.</p>';
    } else {
        function listItemCallback($node, $html, $level) {
            $title = $node['Page']['title'];
            if ($level > 0) {
                $title = "- $title";
            }
            $editLink = $html->link($title,
                array('action' => 'edit', 'id' => $node['Page']['id']),
                array('title' => 'Edit'));
//            $actions = '<span class="actions">'
//                . $html->link('View',
//                    $node['Page']['url'],
//                    array('class' => 'view-page'))
//                . '</span>';
            
            return '<div class="list-item">' . $editLink . '</div>';
        }
        
        echo $list->create($results, array('model' => 'Page', 'class' => 'list selectable-list search-results'));
    }
?>