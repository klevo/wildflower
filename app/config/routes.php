<?php
/* SVN FILE: $Id: routes.php 6311 2008-01-02 06:33:52Z phpnut $ */
/**
 * Short description for file.
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework <http://www.cakephp.org/>
 * Copyright 2005-2008, Cake Software Foundation, Inc.
 *								1785 E. Sahara Avenue, Suite 490-204
 *								Las Vegas, Nevada 89104
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright		Copyright 2005-2008, Cake Software Foundation, Inc.
 * @link				http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package			cake
 * @subpackage		cake.app.config
 * @since			CakePHP(tm) v 0.2.9
 * @version			$Revision: 6311 $
 * @modifiedby		$LastChangedBy: phpnut $
 * @lastmodified	$Date: 2008-01-02 00:33:52 -0600 (Wed, 02 Jan 2008) $
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 */

Router::connect('/', array('controller' => 'pages', 'action' => 'view'));
Router::connect('/' . WILDFLOWER_POSTS_INDEX, array('controller' => 'posts'));
Router::connect('/' . WILDFLOWER_POSTS_INDEX . '/:slug', array('controller' => 'posts', 'action' => 'view'));
Router::connect('/tag/:slug', array('controller' => 'posts', 'action' => 'category'));
Router::connect('/feed', array('controller' => 'posts', 'action' => 'feed'));
Router::connect('/comments/create', array('controller' => 'comments', 'action' => 'create'));
Router::connect('/search', array('controller' => 'dashboards', 'action' => 'search'));
Router::connect('/users/logout', array('controller' => 'users', 'action' => 'logout'));
Router::connect('/assets/:action', array('controller' => 'assets'));
Router::connect('/login', array('controller' => 'users', 'action' => 'login'));
Router::connect('/contact', array('controller' => 'messages', 'action' => 'index'));
Router::connect('/contact/create', array('controller' => 'messages', 'action' => 'create'));
Router::connect('/' . Configure::read('Routing.admin'), array('controller' => 'dashboards', 'action' => 'index', 'prefix' => 'admin', 'admin' => 1));

// Connect everything expect /admin and /pages to PagesController::view
Router::connect('(?!' . Configure::read('Routing.admin') . '|pages|posts)(.*)', array('controller' => 'pages', 'action' => 'view'), array('$2'));

/**
 * REST routes for admin
 */
/*// Default action is edit
Router::connect(
    "/admin/:controller/:id",
    array('admin' => 1, 'prefix' => 'admin', 'action' => 'edit'),
    array('id' => '[0-9]+')
);
// All the other actions
Router::connect(
    "/admin/:controller/:id/:action",
    array('admin' => 1, 'prefix' => 'admin'),
    array('id' => '[0-9]+')
);*/

/**
 * Then we connect url '/test' to our test controller. This is helpfull in
 * developement.
 */
	Router::connect('/tests', array('controller' => 'tests', 'action' => 'index'));
