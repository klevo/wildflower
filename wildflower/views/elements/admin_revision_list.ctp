<?php 
    if (!empty($revisions)) {
        echo 
        '<ul id="revisions" class="list revision-list">';
        
        $first = '<span class="current-revision">&mdash;' . __('current version', true) . '</span>';
        foreach ($revisions as $version) {
            $attr = '';
            if (ListHelper::isOdd()) {
                $attr = ' class="odd"';
            }
            echo 
            "<li$attr>",
            '<div class="list-item">',
            $html->link(__('Revision') . " {$version['Revision']['revision_number']}",
                array('action' => 'edit', 'rev' => $first ? null : $version['Revision']['revision_number'], $version['Revision']['node_id']), null, null, false),
            //"<small>$first, " . __('saved', true) . " " . $time->niceShort($version['Revision']['created']) . " " . __('by', true) . " " . $version['User']['name'] . "</small>",
            "<small>" . sprintf(__('%s saved %s by %s',true),$first,$time->niceShort($version['Revision']['created']),$version['User']['name'])."</small>",
            '</div>',
            '</li>';
            $first = '';
        }
        echo '</ul>';
    }
?>