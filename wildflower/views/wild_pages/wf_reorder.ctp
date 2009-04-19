<h2 class="section"><?php __('Reordering pages'); ?></h2>

<?php
    echo 
    $form->create('WildPage', array('url' => $here)),
    $tree->generate($pages, array('class' => 'WildPage', 'element' => '../wild_pages/_reorder_item')),
    $form->end();
?>

