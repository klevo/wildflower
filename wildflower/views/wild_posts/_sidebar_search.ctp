<?php
    echo
    $form->create('WildPost', array('url' => $html->url(array('action' => 'wf_search', 'base' => false)), 'class' => 'search')),
    $form->input('query', array('label' => __('Find a post by typing', true), 'id' => 'SearchQuery')),
    $form->end();
?>