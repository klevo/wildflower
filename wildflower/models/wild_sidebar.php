<?php
class WildSidebar extends AppModel {
    
    public $hasAndBelongsToMany = array('WildPage');
    
    function findBlogSidebar() {
        return $this->find('first', array('conditions' => array('on_posts' => 1), 'recursive' => -1));
    }
    
}