<?php
class Randomizer {
    
    private $_lorem = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";
    private $_words;
    private $_parentIds = array(0 => null);
    
    function __construct() {
        $this->_words = explode(' ', $this->_lorem);
    }
    
    function title() {
        $len = mt_rand(1, 20);
        $title = '';
        for ($i = 0; $i < $len; $i++) {
            $title .= $this->word() . ' ';
        }
        $title = trim(ucwords($title));
        return $title;
    }
    
    function text() {
        $len = mt_rand(1, 100);
        $text = '';
        for ($i = 0; $i < $len; $i++) {
            $text .= $this->_lorem;
        }
        return $text;
    }
    
    function word() {
        return $this->_words[array_rand($this->_words)];
    }
    
    function parentId($lastId) {
        $this->_parentIds[] = $lastId;
        return $this->_parentIds[array_rand($this->_parentIds)];
    }
    
}