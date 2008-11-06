<?php
Router::connect('/', array('controller' => 'wild_pages', 'action' => 'view', 'plugin' => 'wildflower'));
Router::connect('/' . WILDFLOWER_POSTS_INDEX, array('controller' => 'wild_posts', 'plugin' => 'wildflower'));
Router::connect('/' . WILDFLOWER_POSTS_INDEX . '/:slug', array('controller' => 'wild_posts', 'action' => 'view', 'plugin' => 'wildflower'));

Router::connect('/comments/create', array('controller' => 'wild_comments', 'action' => 'create', 'plugin' => 'wildflower'));

Router::connect('/search', array('controller' => 'wild_dashboards', 'action' => 'search', 'plugin' => 'wildflower'));

$prefix = Configure::read('Wildflower.prefix');
$admin = Configure::read('Routing.admin');

// Connect everything expect /admin and Widlfower /prefix to PagesController::view

Router::connect('(?!' . $admin . '|' . $prefix . '|login|contact|)(.*)', array('controller' => 'wild_pages', 'action' => 'view', 'plugin' => 'wildflower'), array('$2'));

/**
 * Wildflower admin routes
 *
 * Changing Wildflower.prefix in app/plugins/wildflower/config/core.php allows you
 * to change the WF admin url. After this access the admin under /your-prefix.
 */
$wfControllers = array('pages', 'posts', 'dashboards', 'users', 'categories', 'comments', 'assets', 'messages', 'uploads', 'settings');
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

Router::connect('/contact', array('controller' => 'wild_messages', 'action' => 'index', 'plugin' => 'wildflower'));
Router::connect('/contact/create', array('controller' => 'wild_messages', 'action' => 'create', 'plugin' => 'wildflower'));

Router::connect('/' . WILDFLOWER_POSTS_INDEX . '/feed', array('controller' => 'wild_posts', 'action' => 'feed', 'plugin' => 'wildflower'));
