<?php
class Menu extends AppModel {
    
    public $actsAs = array('Containable');
    public $hasMany = array('MenuItem');
    public $validate = array(
        'title' => VALID_NOT_EMPTY,
    );
    
}