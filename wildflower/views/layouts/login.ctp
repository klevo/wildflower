<?php echo $html->doctype('xhtml-strict') ?>
<html lang="en">
<head>
    <?php echo $html->charset(); ?>
    
    <title><?php echo $title_for_layout; ?></title>
    
    <meta name="description" content="" />
    
    <link rel="icon" href="<?php echo $this->webroot;?>favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="<?php echo $this->webroot;?>favicon.ico" type="image/x-icon" />
    
    <?php echo $html->css('login'); ?>
    
</head>
<body>

<div id="wrap">

    <div id="header">
        <h1 id="site-title">
            <?php echo $html->link($siteName, '/', array('title' => __('View site home page', true))) ?>
        </h1>
    </div>
    
    <div id="login">
        <?php echo $content_for_layout; ?>
    </div>
    
    <div id="push"></div>
</div>
    
<p id="footer">
    <?php echo $html->link(__('Powered by Wildflower', true), array('controller' => 'pages', 'action' => 'about')) ?> &bull; 
    <?php echo $html->link(__('Icons by DryIcons', true), 'http://dryicons.com') ?>
</p>
</p>

</body>
</html>
