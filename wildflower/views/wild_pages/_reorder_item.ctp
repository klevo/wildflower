<?php 
    echo hsc($data['WildPage']['title']); 
    $tree->addItemAttribute('id', "page-{$data['WildPage']['id']}");
?>