<?php
class WildMenu extends AppModel {
    
    public $actsAs = array('Containable');
    public $hasMany = array('WildMenuItem');
    public $validate = array(
        'title' => VALID_NOT_EMPTY,
    );
    
}