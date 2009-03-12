<?php
class WildCategory extends AppModel {

    public $actsAs = array(
        'Tree', 
        'Slug' => array('label' => 'title')
    );
    public $validate = array(
        'title' => VALID_NOT_EMPTY
    );
    public $hasAndBelongsToMany = array('WildPost');

}
