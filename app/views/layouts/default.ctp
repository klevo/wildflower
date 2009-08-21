<?php echo $html->doctype('xhtml-strict'); ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <?php echo $html->charset(); ?>
    
    <title><?php echo $title_for_layout; ?></title>
    
    <meta name="description" content="<?php echo isset($descriptionMetaTag) ? $descriptionMetaTag : '' ?>" />
    
    <link rel="shortcut icon" href="<?php echo $this->webroot;?>favicon.ico" type="image/x-icon" />
    <link rel="alternate" type="application/rss+xml" title="<?php echo $siteName; ?> RSS Feed" href="<?php echo $html->url('/' . Configure::read('Wildflower.blogIndex') . '/rss'); ?>" />
    
    <?php echo $html->css('wfsite'); ?>
</head>
<body>
<div id="wrap">

    <div id="header">   
        <h1><?php echo $html->link("<span>$title_for_layout</span>", '/', null, null, false) ?></h1>
        <?php e($html->image('/wildflower/img/logo.jpg')); ?>
    </div>
    
    <hr />
    
    <?php echo $wild->menu('main_menu'); ?>
    
    <hr />
    
    <div id="content">
        <?php echo $content_for_layout; ?>
        <span class="cleaner">&nbsp;</span>
    </div>
    
    <hr />
    
    <div id="footer">
        <p>Powered by <?php echo $html->link('Wildflower', 'http://wf.klevo.sk/'); ?>. Wildflower Logo designed by <?php echo $html->link('Oliver Treend', 'http://www.olivertreend.com/'); ?>. <?php echo $this->element('admin_link'); ?></p>
        
        <?php echo $this->element('debug_notice'); ?>
    </div>
    
</div>
<?php echo $this->element('google_analytics'); ?>
</body>
</html>

