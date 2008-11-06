<?php
$first = '<span class="current-revision">&mdash;current version</span>';
$revision = '';
foreach ($revisions as $version) {
    $revision .= "<li>" .
    '<div class="list-item">' .
    $html->link("Revision {$version['WildRevision']['revision_number']}",
        array('action' => 'wf_edit', $version['WildRevision']['node_id'], $first ? null : $version['WildRevision']['revision_number']), null, null, false) .
    "<small>$first, saved {$time->niceShort($version['WildRevision']['created'])} by {$version['WildUser']['name']}</small>" .
    '</div>' .
    '</li>';
    $first = '';
}

$json = array(
    'time' => $time->niceShort($page['WildPage']['updated']),
    'fullTime' => $time->nice($page['WildPage']['updated']),
    'revision' => $revision,
    'revNumber' => $revisions[0]['WildRevision']['revision_number']
);
echo json_encode($json);
?>