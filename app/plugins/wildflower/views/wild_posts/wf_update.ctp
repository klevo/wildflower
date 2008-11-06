<?php
$first = '<span class="current-revision">&mdash;current version</span>';
$revision = '';
foreach ($revisions as $version) {
    $revision .= "<li>" .
    '<div class="list-item">' .
    $html->link("Revision {$version['WildRevision']['revision_number']}",
        array('action' => 'wf_edit', 'rev' => $first ? null : $version['WildRevision']['revision_number'], $version['WildRevision']['node_id']), null, null, false) .
    "<small>$first, saved {$time->niceShort($version['WildRevision']['created'])} by {$version['WildUser']['name']}</small>" .
    '</div>' .
    '</li>';
    $first = '';
}

$json = array(
    'time' => $time->niceShort($post['WildPost']['updated']),
    'fullTime' => $time->nice($post['WildPost']['updated']),
    'revision' => $revision,
    'revNumber' => $revisions[0]['WildRevision']['revision_number']
);
echo json_encode($json);
?>