<?php
    echo
    $navigation->create(array(
        'All' => array('action' => 'index'),
        'Drafts' => array('action' => 'drafts')
    ), array('id' => 'sub-nav')),
    $this->element('admin_index_toolbar', array('title' => 'Draft Pages', 'model' => 'Page')),
    $list->create($pages, array('model' => 'Page', 'class' => 'list selectable-list'));
?>
