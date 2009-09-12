<?php
class MenuItem extends AppModel {
    
    public $belongsTo = array('Menu');
    // private $_orderCache = array();
    // 
    // function beforeSave() {
    //     parent::beforeSave();
    //     if (isset($this->data['MenuItem']['menu_id']) and is_numeric($this->data['MenuItem']['menu_id'])) {
    //         if (!isset($this->_orderCache[$this->data['MenuItem']['menu_id']])) {
    //             $this->_orderCache[$this->data['MenuItem']['menu_id']] = 0;
    //         } else {
    //             $this->_orderCache[$this->data['MenuItem']['menu_id']]++;
    //         }
    //         $this->data['MenuItem']['order'] = $this->_orderCache[$this->data['MenuItem']['menu_id']];
    //     }
    //     return true;
    // }
    
}