<?php 
    if (!empty($revisions)) {
        echo 
        '<ul id="revisions" class="list revision-list">';
        
        $first = '<span class="current-revision">&mdash;current version</span>';
        foreach ($revisions as $version) {
            $attr = '';
            if (ListHelper::isOdd()) {
                $attr = ' class="odd"';
            }
            echo 
            "<li$attr>",
            '<div class="list-item">',
            $html->link("Revision {$version['WildRevision']['revision_number']}",
                array('action' => 'wf_edit', $version['WildRevision']['node_id'], $first ? null : $version['WildRevision']['revision_number']), null, null, false),
            "<small>$first, saved {$time->niceShort($version['WildRevision']['created'])} by {$version['WildUser']['name']}</small>",
            '</div>',
            '</li>';
            $first = '';
        }
        echo '</ul>';
    } else {
        echo "<p id=\"revisions\">No revisions yet.</p>";
    }
?>