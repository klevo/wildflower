<div class="list-item">
<?php
    $tree->addItemAttribute('id', 'category-' . $data['WildCategory']['id']);
    $tree->addItemAttribute('class', 'level-' . $depth);
    if (ListHelper::isOdd()) {
        $tree->addItemAttribute('class', 'odd');
    }
    echo $html->link($data['WildCategory']['title'], array('action' => 'wf_edit', $data['WildCategory']['id']));
?>
</div>