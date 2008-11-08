<?php
$first = '<span class="current-revision">&mdash;' . __('current version', true) .'</span>';
$revision = '';
foreach ($revisions as $version) {
    $revision .= "<li>" .
    '<div class="list-item">' .
    $html->link("Revision {$version['Revision']['revision_number']}",
        array('action' => 'edit', 'rev' => $first ? null : $version['Revision']['revision_number'], $version['Revision']['node_id']), null, null, false) .
    		//"<small>$first, " . __('saved', true) . " " . $time->niceShort($version['Revision']['created']) . " " . __('by', true) . " " . $version['User']['name'] . "</small>".
        "<small>" . sprintf(__('%s saved %s by %s',true),$first,$time->niceShort($version['Revision']['created']),$version['User']['name'])."</small>".        
    '</div>' .
    '</li>';
    $first = '';
}

$json = array(
    'time' => $time->niceShort($post['Post']['modified']),
    'fullTime' => $time->nice($post['Post']['modified']),
    'revision' => $revision,
    'revNumber' => $version['Revision']['revision_number']
);
echo json_encode($json);
?>