<?php 
if ($isLogged) {
    echo '<span class="admin-link">',
         $html->link('Site admin', '/' . Configure::read('Routing.admin')),
         '</span>';	
}
?>