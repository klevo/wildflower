<?php 
    echo 
    '<span class="admin_link">',
    $html->link('Site admin', '/' . Configure::read('Routing.admin')),
    '</span>';
?>