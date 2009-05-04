<?php
class WildDashboard extends AppModel {

    public $useTable = false;
    static public $classNames = array(
        'WildPage' => array('id', 'title', 'updated'), 
        'WildPost' => array('id', 'title', 'updated'), 
        'WildComment' => array('id', 'name', 'updated'), 
        'WildMessage' => array('id', 'name', 'updated'), 
        'WildAsset' => array('id', 'name', 'updated'),
    );
    
    function findRecentHappening() {
        // Get changed or added pages, posts, comments, contact form messages, files
        $limit = 15;
        $recursive = -1;
        $conditions = null;
        $models = WildDashboard::$classNames;
        $items = array();
        foreach ($models as $model => $fields) {
            $class = ClassRegistry::init($model);
            if (in_array($model, array('WildMessage', 'WildComment'))) {
                //$conditions = array($model . '.spam' => 0);
            }
            $items = array_merge($items, $class->find('all', compact('limit', 'recursive', 'fields', 'conditions')));
        }
        
        // Sort by update time
        function cmp($a, $b) {
            $a = WildDashboard::accessByClassName($a);
            $b = WildDashboard::accessByClassName($b);
            $aTime = strtotime($a['item']['updated']);
            $bTime = strtotime($b['item']['updated']);
            return $bTime - $aTime;
        }
        usort($items, 'cmp');
        
        // Create an array without diff keys
        foreach ($items as &$item) {
            $item = WildDashboard::accessByClassName($item);
        }
        
        return $items;
    }
    
    static function accessByClassName($array) {
        $names = array_keys(WildDashboard::$classNames);
        foreach ($names as $name) {
            if (isset($array[$name])) {
                // Unify everything to title
                if (isset($array[$name]['name'])) {
                    $array[$name]['title'] = $array[$name]['name'];
                }
                return array(
                    'item' => $array[$name],
                    'class' => $name
                );
            }
        }
    }
    
}
