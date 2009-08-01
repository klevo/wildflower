<?php
class Sidebar extends AppModel {
    
    public $hasAndBelongsToMany = array('Page');
    
    function findBlogSidebar() {
        return $this->find('first', array('conditions' => array('on_posts' => 1), 'recursive' => -1));
    }
    
}