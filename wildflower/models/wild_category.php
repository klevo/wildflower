<?php
class WildCategory extends AppModel {

    public $actsAs = array(
        'Containable',
        'Tree', 
        'Slug' => array('label' => 'title')
    );
    public $validate = array(
        'title' => VALID_NOT_EMPTY
    );
    public $hasAndBelongsToMany = array('WildPost');
    
    function beforeSave() {
        parent::beforeSave();
        if (isset($this->data['WildCategory']['parent_id']) and !is_numeric($this->data['WildCategory']['parent_id'])) {
            unset($this->data['WildCategory']['parent_id']);
        }
        return true;
    }

}
