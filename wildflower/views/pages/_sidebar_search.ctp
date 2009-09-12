<?php
    echo
    $form->create('Page', array('url' => $html->url(array('action' => 'admin_search', 'base' => false)), 'class' => 'search')),
    $form->input('query', array('label' => __('Find a page by typing', true), 'id' => 'SearchQuery')),
    $form->end();
?>