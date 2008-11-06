<?php
class WildUtilitiesController extends WildflowerAppController {
    
    public $components = array('Security');
    
    function beforeFilter() {
        parent::beforeFilter();
        $this->Security->requireAuth('wf_index');
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