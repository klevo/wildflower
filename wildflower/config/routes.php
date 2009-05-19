<?php
Router::connect('/', array('controller' => 'wild_pages', 'action' => 'view'));
Router::connect('/app/webroot/', array('controller' => 'wild_pages', 'action' => 'view'));

Router::connect('/comments/create', array('controller' => 'wild_comments', 'action' => 'create'));

Router::connect('/search', array('controller' => 'wild_dashboards', 'action' => 'search'));

$prefix = Configure::read('Wildflower.prefix');
$admin = Configure::read('Routing.admin');

Router::connect('/' . Configure::read('Wildflower.blogIndex') . '/rss', array('controller' => 'wild_posts', 'action' => 'rss'));
Router::connect('/' . Configure::read('Wildflower.blogIndex'), array('controller' => 'wild_posts', 'action' => 'index'));
Router::connect('/' . Configure::read('Wildflower.blogIndex') . '/*', array('controller' => 'wild_posts', 'action' => 'index'));


/**
 * Wildflower admin routes
 *
 * Changing Wildflower.prefix in app/plugins/wildflower/config/core.php allows you
 * to change the WF admin url. After this access the admin under /your-prefix.
 */
$wfControllers = array('pages', 'posts', 'dashboards', 'users', 'categories', 'comments', 'assets', 'messages', 'uploads', 'settings', 'utilities', 'widgets', 'sidebars');
foreach ($wfControllers as $shortcut) {
	Router::connect(
		"/$prefix/$shortcut", 
		array('controller' => "wild_$shortcut", 'action' => 'index', 'prefix' => 'wf')
	);
	
	Router::connect(
		"/$prefix/$shortcut/:action/*", 
		array('controller' => "wild_$shortcut", 'prefix' => 'wf')
	);
}

foreach ($myWfAdminControllers as $controller) {
	Router::connect(
		"/$prefix/$controller", 
		array('controller' => $controller, 'action' => 'index', 'prefix' => 'wf')
	);
	
	Router::connect(
		"/$prefix/$controller/:action/*", 
		array('controller' => $controller, 'prefix' => 'wf')
	);
}

// Dashboard
Router::connect("/$prefix", array('controller' => 'wild_dashboards', 'action' => 'index', 'prefix' => 'wf'));
Router::connect("/$prefix/dashboards/search", array('controller' => 'wild_dashboards', 'action' => 'search', 'prefix' => 'wf'));

// Login screen
Router::connect("/$prefix/login", array('controller' => 'wild_users', 'action' => 'login'));

// Contact form
Router::connect('/contact', array('controller' => 'wild_messages', 'action' => 'index'));
Router::connect('/contact/create', array('controller' => 'wild_messages', 'action' => 'create'));

// RSS
Router::connect('/' . Configure::read('Wildflower.blogIndex') . '/feed', array('controller' => 'wild_posts', 'action' => 'feed'));

// Ultra sexy short SEO friendly post URLs in form of http://my-domain/p/40-char-uuid
Router::connect('/' . Configure::read('Wildflower.postsParent') . '/:slug', array('controller' => 'wild_posts', 'action' => 'view'));
Router::connect('/c/:slug', array('controller' => 'wild_posts', 'action' => 'category'));


// Image thumbnails
Router::connect('/wildflower/thumbnail/*', array('controller' => 'wild_assets', 'action' => 'thumbnail'));
Router::connect('/wildflower/thumbnail_by_id/*', array('controller' => 'wild_assets', 'action' => 'thumbnail_by_id'));

// Site search (pages & posts)
Router::connect('/wildflower/search', array('controller' => 'wild_dashboards', 'action' => 'search'));

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
        		$page['WildPage']['url'], 
        		array('controller' => "wild_pages", 'action' => 'view', 'id' => $page['WildPage']['id'])
        	);
        	// It's children
        	$children = $page['WildPage']['url'] . '/*';
            Router::connect(
                $children, 
                array('controller' => 'wild_pages', 'action' => 'view')
            );
        }
    }
    
    static function update() {
        return Router::requestAction(array('controller' => 'wild_pages', 'action' => 'update_root_cache'), array('return' => 1));
    }
    
    static function write($rootPages = array()) {
        $content = json_encode($rootPages);
        $file = Configure::read('Wildflower.rootPageCache');
        return file_put_contents($file, $content);
    }
    
}