<?php echo $html->doctype('xhtml-strict'); ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <?php echo $html->charset(); ?>
    
    <title><?php echo $title_for_layout; ?></title>
    
	<meta name="description" content="<?php echo isset($pageMeta['descriptionMetaTag']) ? $pageMeta['descriptionMetaTag'] : Configure::read('Wildflower.settings.description'); ?>" />
	<meta name="keywords" content="<?php echo isset($pageMeta['keywordsMetaTag']) ? $pageMeta['keywordsMetaTag'] : Configure::read('Wildflower.settings.keywords'); ?>" />
    
    <link rel="shortcut icon" href="<?php echo $this->webroot;?>favicon.ico" type="image/x-icon" />
    <link rel="alternate" type="application/rss+xml" title="<?php echo $siteName; ?> RSS Feed" href="<?php echo $html->url('/rss'); ?>" />
    
    <?php echo $html->css('wfsite'); ?>
</head>
<body>
<div id="wrap">

    <div id="header">   
        <h1><?php echo $html->link("<span>$title_for_layout</span>", '/', array('escape' => false), null) ?></h1>
        <?php e($html->image('/wildflower/img/logo.jpg')); ?>
        <?php e($html->link('rss', '/rss', array('class' => 'rss'))); ?>
    </div>
    
    <hr />
    
    <?php echo $wild->menu('main_menu'); ?>
    <?php echo $this->element('latest_posts', array('categorySlug'=>'action','categoryLimit'=>4));  ?>
    <?php echo $form->create("Dashboard", array('url' => '/search', 'type' => 'get'));
	    echo $form->input("q", array('label' => false));
	    echo $form->end('Search');
	?>
    
    <hr />
    
    <div id="content">
        <?php echo $content_for_layout; ?>
        <span class="cleaner">&nbsp;</span>
    </div>
    
    <hr />
    
    <div id="footer">
        <p><?= isset($credits)? $credits:'' ?> <?php echo $this->element('admin_link'); ?></p>
        
        <?php echo $this->element('debug_notice'); ?>
    </div>
    
</div>
<?php echo $this->element('google_analytics'); ?>
</body>
</html>

