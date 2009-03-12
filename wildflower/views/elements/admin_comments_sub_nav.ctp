<?php
    echo $navigation->create(array(
        'Approved' => array('action' => 'index'),
        'Spam' => array('action' => 'spam'),
    ), array('id' => 'sub-nav'));
?>