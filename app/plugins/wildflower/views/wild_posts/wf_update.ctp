<?php
$json = array(
    'info' => $time->niceShort($post['WildPost']['updated']),
);

echo json_encode($json);
?>