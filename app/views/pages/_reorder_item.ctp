
<?php 
    echo $html->link($data['Page']['title'], '#Reorder'); 
    $tree->addItemAttribute('id', "page-{$data['Page']['id']}");
?>