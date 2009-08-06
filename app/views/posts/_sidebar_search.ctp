<?php
    echo
    $form->create('Post', array('url' => $html->url(array('action' => 'admin_search', 'base' => false)), 'class' => 'search')),
    $form->input('query', array('label' => __('Find a post by typing', true), 'id' => 'SearchQuery')),
    $form->end();
?>