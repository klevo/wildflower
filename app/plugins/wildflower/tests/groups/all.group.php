<?php
class AllGroupTest extends GroupTest {
    
    public $label = 'All Wildflower tests';

    function AllGroupTest() {
        TestManager::addTestCasesFromDirectory($this, CAKE_CORE_INCLUDE_PATH . DS . 'app' . DS . 'plugins' . DS . 'wildflower' . DS . 'tests' . DS . 'cases');
    }
}
