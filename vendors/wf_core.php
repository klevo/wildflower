<?php
/**
 * Wildflower plugin core configuration
 *
 * This file should be included in app/bootsrap.php.
 * 
 * @package wildflower
 */

/** Constant used in CmsHelper @depracated */
define('CHILD_PAGES_PLEASE', 'CHILD_PAGES_PLEASE');

/** Wildflower config. Access like Configure::read('Wildflower.settingName'); */
Configure::write(array('Wildflower' => array(
    'cookie' => array(
        'name' => 'WildflowerUser',
        'expire' => 2592000,
    ),
    'gzipOutput' => true,
    'uploadsDirectoryName' => 'uploads',
    'uploadDirectory' => APP . WEBROOT_DIR .  DS . 'uploads', // @TODO rename the key
    'rootPageCache' => CACHE . 'wf_root_pages',
    'previewCache' => CACHE . 'wf_previews',
    'thumbnailsCache' => CACHE . 'wf_thumbnails',
    'postsParent' => 'p',
    'blogIndex' => 'blog',
    // Disabling the root page cache may be useful in debugging 
    // (the cache file won't be created, page routes load from the database)
    'disableRootPageCache' => false,
    // 60000% speed increase with pure HTML caching into the webroot
    // @TODO cache expire not implemented yet, so don't use if you can't get around it
    'htmlCache' => false,
)));
