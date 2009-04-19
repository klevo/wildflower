<div class="drop_zone"><?php __('drop here'); ?></div>
<?php 
    echo $html->link($data['WildPage']['title'], '#Reorder'); 
    $tree->addItemAttribute('id', "page-{$data['WildPage']['id']}");
?>