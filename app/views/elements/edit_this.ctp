<?php 
if ($isLogged) {
	$controller = isset($controller) ? $controller : $this->params['controller'];
    echo '<p class="edit-this">',
         $html->link('Edit', array('controller' => $controller, 'action' => 'wf_edit', $id)),
         '</p>';	
}
?>