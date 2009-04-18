<?php
class WildSidebarsController extends AppController {
    
    function beforeFilter() {
        parent::beforeFilter();
        $this->pageTitle = 'Sidebars';
    }
    
    function wf_index() {
        $sidebars = $this->WildSidebar->find('all');
        $this->set(compact('sidebars'));
    }
    
    function wf_add() {
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
        if (!empty($this->data)) {
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
    
}