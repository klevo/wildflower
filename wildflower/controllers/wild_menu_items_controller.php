<?php
class WildMenuItemsController extends AppController {
    
    function delete($id) {
        $this->WildMenuItem->recursive = -1;
        $this->WildMenuItem->id = $id;
        $item = $this->WildMenuItem->read();
        if (!empty($item)) {
            $this->WildMenuItem->del($id);
        }
        $this->redirect(array('controller' => 'wild_menus', 'action' => 'edit', $item['WildMenuItem']['wild_menu_id']));
    }
    
}