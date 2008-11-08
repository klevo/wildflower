<div class="list-item">
<?php
    $tree->addItemAttribute('id', 'category-' . $data['Category']['id']);
    $tree->addItemAttribute('class', 'level-' . $depth);
    if (ListHelper::isOdd()) {
        $tree->addItemAttribute('class', 'odd');
    }
    echo $html->link($data['Category']['title'], array('action' => 'edit', 'id' => $data['Category']['id']));
?>
</div>