<div class="list-item">
<?php
    $tree->addItemAttribute('id', 'page-' . $data['Page']['id']);
    $tree->addItemAttribute('class', 'level-' . $depth);
    if (ListHelper::isOdd()) {
        $tree->addItemAttribute('class', 'odd');
    }
    echo $html->link($data['Page']['title'], array('action' => 'edit', 'id' => $data['Page']['id']),
            array('title' => __('Edit', true))); 
    if ($data['Page']['draft']) {
        echo '<small class="draft-status">' . __('Draft', true) . '</small>';
    }            
?>
</div>
