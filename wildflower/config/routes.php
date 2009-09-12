<?php
/**
 * Wildflower routes
 *
 * Wildflower reservers these URL's:
 */
 
// Home page
Router::connect('/', array('controller' => 'pages', 'action' => 'view'));
Router::connect('/app/webroot/', array('controller' => 'pages', 'action' => 'view'));

// Posts section
Router::connect('/rss', array('controller' => 'posts', 'action' => 'rss'));
Router::connect('/' . Configure::read('Wildflower.blogIndex'), array('controller' => 'posts', 'action' => 'index'));
Router::connect('/' . Configure::read('Wildflower.blogIndex') . '/*', array('controller' => 'posts', 'action' => 'index'));
Router::connect('/' . Configure::read('Wildflower.postsParent') . '/:slug', array('controller' => 'posts', 'action' => 'view'));
Router::connect('/c/:slug', array('controller' => 'posts', 'action' => 'category'));

// Wildflower admin routes
$prefix = Configure::read('Routing.admin');
Router::connect("/$prefix", array('controller' => 'dashboards', 'action' => 'index', 'admin' => true));

// Image thumbnails
// @TODO shorten to '/i/*'
Router::connect('/wildflower/thumbnail/*', array('controller' => 'assets', 'action' => 'thumbnail'));
Router::connect('/wildflower/thumbnail_by_id/*', array('controller' => 'assets', 'action' => 'thumbnail_by_id'));

// Connect root pages slugs
App::import('Vendor', 'WfRootPagesCache', array('file' => 'WfRootPagesCache.php'));
WildflowerRootPagesCache::connect();


/**
 * Your routes here...
 */

