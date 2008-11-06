<?php
class WildUtilitiesController extends WildflowerAppController {
    
    public $components = array('Security');
    
    function beforeFilter() {
        parent::beforeFilter();
        
        // This stuff is only avaible in debug modes
        if (Configure::read('debug') < 1) {
            return $this->do404();
        }
        
        $this->Security->requireAuth('wf_index');
        $this->pageTitle = 'Developer Utilities';
    }
        
    function wf_index() {
        if (!empty($this->data)) {
            $addWhat = '_massAdd' . ucwords($this->data[$this->modelClass]['what']);
            $howMany = intval($this->data[$this->modelClass]['how_many']);
            $this->{$addWhat}($howMany);
        }
    }
    
    function _massAddPosts($howMany = 10) {
        
    }
    
}