<?php
class WildCategory extends WildflowerAppModel {

    public $actsAs = array(
        'Tree', 
        'Wildflower.Slug' => array('label' => 'title')
    );
    public $validate = array(
        'title' => VALID_NOT_EMPTY
    );
    public $hasAndBelongsToMany = array(
        'WildPost' => array(
            'className' => 'Wildflower.WildPost',
        )
    );

}
