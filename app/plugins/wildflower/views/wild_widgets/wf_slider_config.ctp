<?php
    echo 
    $form->create('Widget', array('url' => 'update')),
    var_dump($this->data),
    $form->end('Save');
?>