<?php
class WildDashboard extends AppModel {

    public $useTable = false;
    static public $classNames = array(
        'WildPage' => array('title', 'updated'), 
        'WildPost' => array('title', 'updated'), 
        'WildComment' => array('name', 'updated'), 
        'WildMessage' => array('name', 'updated'), 
        'WildAsset' => array('name', 'updated'),
    );
    
    function findRecentHappening() {
        // Get changed or added pages, posts, comments, contact form messages, files
        $limit = 15;
        $recursive = -1;
        $models = WildDashboard::$classNames;
        $items = array();
        foreach ($models as $model => $fields) {
            $class = ClassRegistry::init($model);
            $items = array_merge($items, $class->find('all', compact('limit', 'recursive', 'fields')));
        }
        
        // Sort by update time
        function cmp($a, $b) {
            $a = WildDashboard::accessByClassName($a);
            $b = WildDashboard::accessByClassName($b);
            $aTime = strtotime($a['updated']);
            $bTime = strtotime($b['updated']);
            return $aTime - $bTime;
        }
        usort($items, 'cmp');
        
        return $items;
    }
    
    static function accessByClassName($array) {
        $names = array_keys(WildDashboard::$classNames);
        foreach ($names as $name) {
            if (isset($array[$name])) {
                return $array[$name];
            }
        }
    }
    
}
