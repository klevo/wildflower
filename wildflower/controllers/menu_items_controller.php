<?php
class MenuItemsController extends AppController {
    
    function admin_delete($id) {
        $this->MenuItem->recursive = -1;
        $this->MenuItem->id = $id;
        $item = $this->MenuItem->read();
        if (!empty($item)) {
            $this->MenuItem->delete($id);
        }
        $this->redirect(array('controller' => 'menus', 'action' => 'edit', $item['MenuItem']['menu_id']));
    }
    
}