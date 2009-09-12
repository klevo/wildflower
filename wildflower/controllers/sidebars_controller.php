<?php
class SidebarsController extends AppController {
    
    function beforeFilter() {
        parent::beforeFilter();
        $this->pageTitle = __('Modules', true);
        $this->_bindCustomModel();
    }
    
    function admin_index() {
        $sidebars = $this->Sidebar->find('all');
        $menus = ClassRegistry::init('Menu')->find('all', array('recursive' => -1));
        $this->set(compact('sidebars', 'menus'));
    }
    
    function admin_add() {
        $this->_bindCustomModel();
        
        if (!empty($this->data)) {
            if ($this->Sidebar->save($this->data)) {
                return $this->redirect(array('action' => 'index'));
            }
        }
        
        $pages = $this->Sidebar->Page->find('all', array(
            'order' => 'lft ASC', 
            'recursive' => -1, 
        ));
        $inPages = array();
        $this->set(compact('pages', 'inPages'));
    }    
    
    function admin_edit($id) {
        $this->_bindCustomModel();
        
        if (!empty($this->data)) {
            //var_dump($this->data);die();
            if ($this->Sidebar->save($this->data)) {
                return $this->redirect(array('action' => 'edit', $id));
            }
        }
        
        $this->data = $this->Sidebar->findById($id);
        
        $pages = $this->Sidebar->Page->find('all', array(
            'order' => 'lft ASC', 
            'recursive' => -1, 
        ));
        $inPages = Set::extract($this->data['Page'], '{n}.id');
        $this->set(compact('pages', 'inPages'));
    }
    
    private function _bindCustomModel() {
        // @TODO add more options to Configure, so the details of the association can be defined
        // Custom associations
        // Define as Configure::write('App.customSidebarAssociations', array('MyModel', 'MyModel2')); in core.php
        $models = Configure::read('App.customSidebarAssociations');
        if (!empty($models)) {
            $this->Sidebar->bindModel(array('hasAndBelongsToMany' => $models));
        }
    }
    
}