<?php
/**
 * Wildflower CMS core configuration
 * 
 */

// Maximum lenght of the content snippet in an admin table cell
Configure::write('Wildflower.table.maxlength', 20);

// Maximum lenght of the parent page select box item
Configure::write('Wildflower.pages.maxparents', 100);

// Login cookie
Configure::write('Wildflower.cookie.name', 'WildflowerUser');
Configure::write('Wildflower.cookie.expire', 2592000); // 30 days

// Turn Gzip compression on/off
Configure::write('Wildflower.useGzip', false);

// Uploads directory
Configure::write('Wildflower.uploadDirectory', APP . WEBROOT_DIR .  DS . 'uploads');

// Admin prefix
Configure::write('Wildflower.prefix', 'wf');

/** Name of the posts index page in the URL */
define('WILDFLOWER_POSTS_INDEX', 'blog');
/** Contastant used in CmsHelper */
define('CHILD_PAGES_PLEASE', 'CHILD_PAGES_PLEASE');
