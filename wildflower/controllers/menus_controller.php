<?php
class MenusController extends AppController {
    
    public $pageTitle = 'Navigation';
    
    function admin_add() {
        if (!empty($this->data)) {
            $this->_addOrderToItems();
            $this->data['Menu']['slug'] = Inflector::slug($this->data['Menu']['title']);
            if ($this->Menu->saveAll($this->data)) {
                $this->Session->setFlash(__('Menu created.', true));
                return $this->redirect(array('action' => 'index', 'controller' => 'sidebars'));
            }
        }
    }
    
    function admin_edit($id) {
        if (!empty($this->data)) {
            $this->_addOrderToItems();
            //var_dump($this->data);die();
            if ($this->Menu->saveAll($this->data)) {
                $this->Session->setFlash(__('Menu updated.', true));
                return $this->redirect(array('action' => 'edit', $id));
            }
        } else {
            $this->Menu->contain(array('MenuItem' => array('order' => 'MenuItem.order ASC')));
            $this->data = $this->Menu->findById($id);
        }
    }
    
    private function _addOrderToItems() {
        $pos = 0;
        foreach ($this->data['MenuItem'] as &$item) {
            $item['order'] = $pos;
            $pos++;
        }
    }
    
}