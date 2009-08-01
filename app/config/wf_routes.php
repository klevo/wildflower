<?php
Router::connect('/', array('controller' => 'pages', 'action' => 'view'));
Router::connect('/app/webroot/', array('controller' => 'pages', 'action' => 'view'));

Router::connect('/comments/create', array('controller' => 'comments', 'action' => 'create'));

Router::connect('/search', array('controller' => 'dashboards', 'action' => 'search'));

$prefix = Configure::read('Wildflower.prefix');
$admin = Configure::read('Routing.admin');

Router::connect('/' . Configure::read('Wildflower.blogIndex') . '/rss', array('controller' => 'posts', 'action' => 'rss'));
Router::connect('/' . Configure::read('Wildflower.blogIndex'), array('controller' => 'posts', 'action' => 'index'));
Router::connect('/' . Configure::read('Wildflower.blogIndex') . '/*', array('controller' => 'posts', 'action' => 'index'));


/**
 * Wildflower admin routes
 *
 * Changing Wildflower.prefix in app/plugins/wildflower/config/core.php allows you
 * to change the WF admin url. After this access the admin under /your-prefix.
 */

// Dashboard
Router::connect("/$prefix", array('controller' => 'dashboards', 'action' => 'index', 'prefix' => 'wf'));
Router::connect("/$prefix/dashboards/search", array('controller' => 'dashboards', 'action' => 'search', 'prefix' => 'wf'));

// Login screen
Router::connect("/$prefix/login", array('controller' => 'users', 'action' => 'login'));

// Contact form
Router::connect('/contact', array('controller' => 'messages', 'action' => 'index'));
Router::connect('/contact/create', array('controller' => 'messages', 'action' => 'create'));

// RSS
Router::connect('/' . Configure::read('Wildflower.blogIndex') . '/feed', array('controller' => 'posts', 'action' => 'feed'));

// Posts
Router::connect('/' . Configure::read('Wildflower.postsParent') . '/:slug', array('controller' => 'posts', 'action' => 'view'));
Router::connect('/c/:slug', array('controller' => 'posts', 'action' => 'category'));


// Image thumbnails
Router::connect('/wildflower/thumbnail/*', array('controller' => 'assets', 'action' => 'thumbnail'));
Router::connect('/wildflower/thumbnail_by_id/*', array('controller' => 'assets', 'action' => 'thumbnail_by_id'));

// Site search (pages & posts)
Router::connect('/wildflower/search', array('controller' => 'dashboards', 'action' => 'search'));

WildflowerRootPagesCache::connect();

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