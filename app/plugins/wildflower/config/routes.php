<?php
Router::connect('/', array('controller' => 'wild_pages', 'action' => 'view', 'plugin' => 'wildflower'));

Router::connect('/comments/create', array('controller' => 'wild_comments', 'action' => 'create', 'plugin' => 'wildflower'));

Router::connect('/search', array('controller' => 'wild_dashboards', 'action' => 'search', 'plugin' => 'wildflower'));

$prefix = Configure::read('Wildflower.prefix');
$admin = Configure::read('Routing.admin');

/**
 * Wildflower admin routes
 *
 * Changing Wildflower.prefix in app/plugins/wildflower/config/core.php allows you
 * to change the WF admin url. After this access the admin under /your-prefix.
 */
$wfControllers = array('pages', 'posts', 'dashboards', 'users', 'categories', 'comments', 'assets', 'messages', 'uploads', 'settings', 'utilities');
foreach ($wfControllers as $shortcut) {
	Router::connect(
		"/$prefix/$shortcut", 
		array('plugin' => 'wildflower', 'controller' => "wild_$shortcut", 'action' => 'index', 'prefix' => 'wf')
	);
	
	Router::connect(
		"/$prefix/$shortcut/:action/*", 
		array('plugin' => 'wildflower', 'controller' => "wild_$shortcut", 'prefix' => 'wf')
	);
}

// Dashboard
Router::connect("/$prefix", array('plugin' => 'wildflower', 'controller' => "wild_dashboards", 'action' => 'index', 'prefix' => 'wf'));

// Login screen
Router::connect('/login', array('controller' => 'wild_users', 'action' => 'login', 'plugin' => 'wildflower'));

// Contact form
Router::connect('/contact', array('controller' => 'wild_messages', 'action' => 'index', 'plugin' => 'wildflower'));
Router::connect('/contact/create', array('controller' => 'wild_messages', 'action' => 'create', 'plugin' => 'wildflower'));

// RSS
Router::connect('/' . Configure::read('Wildflower.blogIndex') . '/feed', array('controller' => 'wild_posts', 'action' => 'feed', 'plugin' => 'wildflower'));

// Ultra sexy short SEO friendly post URLs in form of http://my-domain/p/40-char-uuid
Router::connect('/' . Configure::read('Wildflower.postsParent') . '/:uuid', array('controller' => 'wild_posts', 'action' => 'view', 'plugin' => 'wildflower'));
Router::connect('/' . Configure::read('Wildflower.blogIndex'), array('controller' => 'wild_posts', 'action' => 'index', 'plugin' => 'wildflower'));
Router::connect('/' . Configure::read('Wildflower.blogIndex') . '/feed', array('controller' => 'wild_posts', 'action' => 'feed', 'plugin' => 'wildflower'));

WildflowerRootPagesCache::connectRootPages();

/**
 * Wildflower routes cache API
 *
 * @package wildflower
 */
class WildflowerRootPagesCache {
    
    static function connectRootPages() {
        $file = Configure::read('Wildflower.rootPageCache');
        $rootPages = array();
        
        if (file_exists($file)) {
            $content = file_get_contents($file);
            $rootPages = json_decode($content, true);
        } else {
            // Create the file if it does not exist
            return fopen($file, 'w');
        };
        
        if (empty($rootPages)) return;

        foreach ($rootPages as $page) {
            // Root page
            Router::connect(
        		(string)$page['WildPage']['url'], 
        		array('plugin' => 'wildflower', 'controller' => "wild_pages", 'action' => 'view', 'id' => $page['WildPage']['id'])
        	);
        	// It's children
        	$children = $page['WildPage']['url'] . '/(.*)';
            Router::connect(
                $children, 
                array('plugin' => 'wildflower', 'controller' => "wild_pages", 'action' => 'view'),
                array('$1')
            );
        }
    }
    
    static function update($rootPages) {
        $file = Configure::read('Wildflower.rootPageCache');
        $content = json_encode($rootPages);
        return file_put_contents($file, $content);
    }
    
}