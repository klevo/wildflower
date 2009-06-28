<?php
class WildMenuItem extends AppModel {
    
    public $belongsTo = array('WildMenu');
    // private $_orderCache = array();
    // 
    // function beforeSave() {
    //     parent::beforeSave();
    //     if (isset($this->data['WildMenuItem']['wild_menu_id']) and is_numeric($this->data['WildMenuItem']['wild_menu_id'])) {
    //         if (!isset($this->_orderCache[$this->data['WildMenuItem']['wild_menu_id']])) {
    //             $this->_orderCache[$this->data['WildMenuItem']['wild_menu_id']] = 0;
    //         } else {
    //             $this->_orderCache[$this->data['WildMenuItem']['wild_menu_id']]++;
    //         }
    //         $this->data['WildMenuItem']['order'] = $this->_orderCache[$this->data['WildMenuItem']['wild_menu_id']];
    //     }
    //     return true;
    // }
    
}