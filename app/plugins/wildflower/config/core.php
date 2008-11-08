<?php
/**
 * Wildflower plugin core configuration
 *
 * This file is automatically loaded by WF's bootstrap.
 * 
 * @package wildflower
 */

/** Name of the posts index page in the URL */
define('WILDFLOWER_POSTS_INDEX', 'blog');
/** Contastant used in CmsHelper */
define('CHILD_PAGES_PLEASE', 'CHILD_PAGES_PLEASE');

Configure::write(array('Wildflower' => array(
    'cookie' => array(
        'name' => 'WildflowerUser',
        'expire' => 2592000,
    ),
    'useGzip' => false,
    'uploadDirectory' => APP . WEBROOT_DIR .  DS . 'uploads',
    'prefix' => 'wf',
    'rootPageCache' => CACHE . 'wf-root-pages',
)));
