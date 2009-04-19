<h2 class="section"><?php __('Reordering pages'); ?></h2>


<?php
    function list_item($data) {
        var_dump($data);
    }

    echo 
    $form->create('WildPage', array('url' => $here)),
    $tree->generate($pages, array('class' => 'WildPage', 'callback' => 'list_item')),
    $form->end();
?>

