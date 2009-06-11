<?php
class WildMenusController extends AppController {
    
    public $pageTitle = 'Navigation';
    
    function wf_add() {
        if (!empty($this->data)) {
            $this->_addOrderToItems();
            $this->data['WildMenu']['slug'] = Inflector::slug($this->data['WildMenu']['title']);
            if ($this->WildMenu->saveAll($this->data)) {
                $this->Session->setFlash(__('Menu created.', true));
                return $this->redirect(array('action' => 'index', 'controller' => 'wild_sidebars'));
            }
        }
    }
    
    function wf_edit($id) {
        if (!empty($this->data)) {
            $this->_addOrderToItems();
            //var_dump($this->data);die();
            if ($this->WildMenu->saveAll($this->data)) {
                $this->Session->setFlash(__('Menu updated.', true));
                return $this->redirect(array('action' => 'edit', $id));
            }
        } else {
            $this->WildMenu->contain(array('WildMenuItem' => array('order' => 'WildMenuItem.order ASC')));
            $this->data = $this->WildMenu->findById($id);
        }
    }
    
    private function _addOrderToItems() {
        $pos = 0;
        foreach ($this->data['WildMenuItem'] as &$item) {
            $item['order'] = $pos;
            $pos++;
        }
    }
    
}