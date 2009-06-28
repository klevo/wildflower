
<?php 
    echo $html->link($data['WildPage']['title'], '#Reorder'); 
    $tree->addItemAttribute('id', "page-{$data['WildPage']['id']}");
?>