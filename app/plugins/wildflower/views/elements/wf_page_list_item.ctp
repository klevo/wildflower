<div class="list-item">
<?php
    $tree->addItemAttribute('id', 'page-' . $data['WildPage']['id']);
    $tree->addItemAttribute('class', 'level-' . $depth);
    if (ListHelper::isOdd()) {
        $tree->addItemAttribute('class', 'odd');
    }
    
    echo $html->link($data['WildPage']['title'], array('action' => 'edit', $data['WildPage']['id']), array('title' => 'Edit this page')); 
            
    // Draft status        
    if ($data['WildPage']['draft']) {
        echo ' <small class="draft-status">Draft</small>';
    }            
?>
</div>
