<?php
class WildCategory extends WildflowerAppModel {

    public $useTable = 'categories';

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
            'joinTable' => 'categories_posts',
            'foreignKey' => 'category_id',
            'associationForeignKey' => 'post_id',
        )
    );

}
