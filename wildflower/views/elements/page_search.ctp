<?php 
echo $form->create('Page', array('url' => array('action' => 'search', 'view' => $isTableView ? 'table' : null), 'class' => 'search')),
    $form->input('query', array('label' => '<span>' . __('Type to search') . '</span>', 'between' => ' ', 'class' => 'search-query'), null, null, false),
    $form->end(__('Search', true));
?>
