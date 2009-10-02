<?php echo $html->doctype('xhtml-strict'); ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <?php echo $html->charset(); ?>
    
    <title><?php echo $title_for_layout; ?></title>
    
    <meta name="description" content="<?php echo isset($descriptionMetaTag) ? $descriptionMetaTag : '' ?>" />
    
    <link rel="icon" href="<?php echo $this->webroot;?>wildflower/img/kruger/dkico.ico" type="image/x-icon" /> 
    <link rel="shortcut icon" href="<?php echo $this->webroot;?>wildflower/img/kruger/dkico.ico" type="image/x-icon" />
    <link rel="alternate" type="application/rss+xml" title="<?php echo Configure::read('Wildflower.settings.site_name'); ?> RSS Feed" href="<?php echo $html->url('/' . Configure::read('Wildflower.blogIndex') . '/rss'); ?>" />
    
    <?php echo $html->css('../wildflower/css/kruger/reset.css'); ?>
    <?php echo $html->css('../wildflower/css/kruger/stylesheet.css'); ?>
</head>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>David Kr&uuml;ger :: Web Design &amp; Development</title>


<!-- Cascading Style Sheets -->
<link rel="stylesheet" href="css/reset.css" type="text/css" />
<link rel="stylesheet" href="css/stylesheet.css" type="text/css" />
<link rel="stylesheet" href="css/sIFR-screen.css" type="text/css" media="screen" />
<link rel="stylesheet" href="css/sIFR-print.css" type="text/css" media="print" />

<!-- JavaScript files -->
<script src="js/sifr.lite.js" type="text/javascript"></script>

</head>

<body>
    <!-- HEADER -->
	<div class="header">
    	<div class="web-title">
            <h1 id="wildflower_logo"><?php echo $html->link("<span>$siteName</span>", '/', array('escape' => false)); ?></h1>
        </div>
        <?php echo $wild->menu('main_menu', array('class' => 'topnav')); ?>
        <div class="clear"></div>
        
        <h2 class="intro">A CakePHP<br /> Content Management System</h2>        
        <p class="intro">Pages, posts, categories, RSS, themes, MVC, database migrations. All good stuff.</p>
    
    </div>
    <!-- HEADER -->

    <!-- CONTAINER -->
    <div id="container" class="three_col_wide_left"> 
        <?php echo $content_for_layout; ?>
        <span class="cleaner">&nbsp;</span>
    </div>
    <!-- CONTAINER -->

    <!-- FOOTER -->
  	<div class="footer">Theme by David Kruger<span>|</span>  
    <a href="http://jigsaw.w3.org/css-validator/validator?uri=http%3A%2F%2Fwww.tapiti.net%2Fdk%2F&profile=css21&usermedium=all&warning=1"><img alt="Valid CSS" src="images/valid-css.png"/></a> 
    <a href="http://validator.w3.org/check?uri=http%3A%2F%2Fwww.tapiti.net%2Fdk%2F"><img alt="Valid (X)HTML" src="images/valid-html.png"/></a> 
    <?php echo $this->element('debug_notice'); ?>
    </div>
    <!-- FOOTER -->

<?php echo $this->element('google_analytics'); ?>
</body>
</html>