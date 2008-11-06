<?php
class AllGroupTest extends GroupTest {
    
    var $label = 'All app/ tests';

    function AllGroupTest() {
        TestManager::addTestCasesFromDirectory($this, CAKE_CORE_INCLUDE_PATH . DS . 'app' . DS . 'test' . DS . 'cases');
    }
}
?>