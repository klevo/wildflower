<?php
/**
 * Wildflower root pages routes cache API
 * 
 * Pages without a parent are each passed to Route::connect().
 *
 * @package wildflower
 */
class WildflowerRootPagesCache {
    
    static function connect() {
        $file = Configure::read('Wildflower.rootPageCache');
        $rootPages = array();
        
        if (file_exists($file)) {
            $content = file_get_contents($file);
            $rootPages = json_decode($content, true);
        } else {
            $rootPages = self::update();
        };
        
        if (!is_array($rootPages)) {
            $rootPages = self::update();
        }

        foreach ($rootPages as $page) {
            // Root page
            Router::connect(
        		$page['Page']['url'], 
        		array('controller' => "pages", 'action' => 'view', 'id' => $page['Page']['id'])
        	);
        	// It's children
        	$children = $page['Page']['url'] . '/*';
            Router::connect(
                $children, 
                array('controller' => 'pages', 'action' => 'view')
            );
        }
    }
    
    static function update() {
        return Router::requestAction(array('controller' => 'pages', 'action' => 'update_root_cache'), array('return' => 1));
    }
    
    static function write($rootPages = array()) {
        $content = json_encode($rootPages);
        $file = Configure::read('Wildflower.rootPageCache');
        return file_put_contents($file, $content);
    }
    
}