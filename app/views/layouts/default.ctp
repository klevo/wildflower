<?php echo $html->doctype('xhtml-strict') ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <?php echo $html->charset(); ?>
    
    <title><?php echo $title_for_layout; ?></title>
    
    <meta name="description" content="<?php echo isset($descriptionMetaTag) ? $descriptionMetaTag : '' ?>" />
    
    <link rel="icon" href="<?php echo $this->webroot;?>favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="<?php echo $this->webroot;?>favicon.ico" type="image/x-icon" />
    <link rel="alternate" type="application/rss+xml" title="<?php echo $siteName; ?> Feed" href="<?php echo $html->url('/posts/feed'); ?>" />
    
    <?php 
	    echo $html->css(array(
	        'wildflower/main'
	    ));
    ?>
        
    <script type="text/javascript">
        BASE = '<?php echo $this->base ?>';
    </script>
</head>
<body>

    <div id="wrap">
    
        <div id="header">   
            <h1><?php echo $html->link("<span>$title_for_layout</span>", '/', null, null, false) ?></h1>
        </div>
        
        <hr />
        
        <?php 
            echo $navigation->create(array(
                'Home' => '/',
                'Feature tour' => '/feature-tour',
                'Blog' => '/blog',
                'Documentation' => '/documentation',
                'Contact' => '/contact'
            ));
        ?>
        
        <hr />
        
        <div id="content">
            <?php echo $content_for_layout; ?>
            <span class="cleaner">&nbsp;</span>
        </div>
        
        <hr />
        
        <div id="footer">
	        <p>Powered by <?php 
	           echo $html->image('wildflower/small-logo.gif', array('alt' => 'Wildflower', 'class' => 'wf-icon')), 
	           ' ', 
	           $html->link('Wildflower', '/'),
	           '. ',
	           $this->element('admin_link') ?></p>
	        
	        <?php echo $this->element('debug_notice') ?>
	    </div>
        
    </div>
    
    <?php echo $this->element('google_analytics') ?>
    
</body>
</html>

