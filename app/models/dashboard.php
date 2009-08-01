<?php
class Dashboard extends AppModel {

    public $useTable = false;
    static public $classNames = array(
        'Page' => array('id', 'title', 'updated'), 
        'Post' => array('id', 'title', 'updated'), 
        'Comment' => array('id', 'name', 'updated'), 
        'Message' => array('id', 'name', 'updated'), 
        'Asset' => array('id', 'name', 'updated'),
    );
    
    function findRecentHappening() {
        // Get changed or added pages, posts, comments, contact form messages, files
        $limit = 15;
        $recursive = -1;
        $conditions = null;
        $models = Dashboard::$classNames;
        $items = array();
        foreach ($models as $model => $fields) {
            $class = ClassRegistry::init($model);
            if (in_array($model, array('Message', 'Comment'))) {
                //$conditions = array($model . '.spam' => 0);
            }
            $items = array_merge($items, $class->find('all', compact('limit', 'recursive', 'fields', 'conditions')));
        }
        
        // Sort by update time
        function cmp($a, $b) {
            $a = Dashboard::accessByClassName($a);
            $b = Dashboard::accessByClassName($b);
            $aTime = strtotime($a['item']['updated']);
            $bTime = strtotime($b['item']['updated']);
            return $bTime - $aTime;
        }
        usort($items, 'cmp');
        
        // Create an array without diff keys
        foreach ($items as &$item) {
            $item = Dashboard::accessByClassName($item);
        }
        
        return $items;
    }
    
    static function accessByClassName($array) {
        $names = array_keys(Dashboard::$classNames);
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
