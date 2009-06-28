<?php
class WildSidebarsController extends AppController {
    
    function beforeFilter() {
        parent::beforeFilter();
        $this->pageTitle = __('Modules', true);
        $this->_bindCustomModel();
    }
    
    function wf_index() {
        $sidebars = $this->WildSidebar->find('all');
        $menus = ClassRegistry::init('WildMenu')->find('all', array('recursive' => -1));
        $this->set(compact('sidebars', 'menus'));
    }
    
    function wf_add() {
        $this->_bindCustomModel();
        
        if (!empty($this->data)) {
            if ($this->WildSidebar->save($this->data)) {
                return $this->redirect(array('action' => 'index'));
            }
        }
        
        $pages = $this->WildSidebar->WildPage->find('all', array(
            'order' => 'lft ASC', 
            'recursive' => -1, 
        ));
        $inPages = array();
        $this->set(compact('pages', 'inPages'));
    }    
    
    function wf_edit($id) {
        $this->_bindCustomModel();
        
        if (!empty($this->data)) {
            //var_dump($this->data);die();
            if ($this->WildSidebar->save($this->data)) {
                return $this->redirect(array('action' => 'edit', $id));
            }
        }
        
        $this->data = $this->WildSidebar->findById($id);
        
        $pages = $this->WildSidebar->WildPage->find('all', array(
            'order' => 'lft ASC', 
            'recursive' => -1, 
        ));
        $inPages = Set::extract($this->data['WildPage'], '{n}.id');
        $this->set(compact('pages', 'inPages'));
    }
    
    private function _bindCustomModel() {
        // @TODO add more options to Configure, so the details of the association can be defined
        // Custom associations
        // Define as Configure::write('App.customSidebarAssociations', array('MyModel', 'MyModel2')); in core.php
        $models = Configure::read('App.customSidebarAssociations');
        if (!empty($models)) {
            $this->WildSidebar->bindModel(array('hasAndBelongsToMany' => $models));
        }
    }
    
}