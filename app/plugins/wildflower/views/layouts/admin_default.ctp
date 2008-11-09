<?php echo $html->doctype('xhtml-strict') ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <?php echo $html->charset(); ?>
	
	<title><?php echo $title_for_layout; ?></title>
	
	<meta name="description" content="" />
	
    <link rel="icon" href="<?php echo $this->webroot; ?>favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="<?php echo $this->webroot; ?>favicon.ico" type="image/x-icon" />
	
	<?php 
        echo
        // Load your CSS files here
        $html->css(array(
            '/wildflower/css/wf.main',
            '/wildflower/css/wf.switcher',
        )),
        // Load your Javascript here
        $javascript->link(array(
            'tiny_mce/tiny_mce',
        ));
    ?>
    
    <!--[if lte IE 6]>
        <?php
            // CSS file for Microsoft Internet Explorer 6 and lower
            echo $html->css('/wildflower/css/wf.ie6');
        ?>
    <![endif]-->    
    <!--[if IE 7]>
        <?php
            // CSS file for Microsoft Internet Explorer 7
            echo $html->css('/wildflower/css/wf.ie7');
        ?>
    <![endif]-->
    
    <?php echo $this->element('tiny_mce') ?>
    
    <!-- JQuery Light MVC -->
    <script type="text/javascript" src="<?php echo $html->url(array('controller' => 'wild_assets', 'action' => 'jlm', 'plugin' => 'wildflower')); ?>"></script>
    <script type="text/javascript">
    //<![CDATA[
        $.jlm.config({
            base: '<?php echo $this->base ?>',
            controller: '<?php echo $this->params['controller'] ?>',
            action: '<?php echo $this->params['action'] ?>', 
            prefix: '<?php echo Configure::read('Wildflower.prefix') ?>' 
        });

        $(function() {
           $.jlm.dispatch(); 
        });
    //]]>
    </script>
    
</head>
<body>


<div id="header">
    <h1 id="site-title">
        <?php echo $html->link($siteName, '/', array('title' => 'View site home page')) ?>
    </h1>
    
    <div id="login-info">
        <?php echo $html->link('Log out', array('controller' => 'wild_users', 'action' => 'logout'), array('id' => 'logout')); ?>
    </div>
    
    <?php 
        echo $navigation->create(array(
            'Dashboard' => '/' . Configure::read('Wildflower.prefix'),
            'Blog' => array('controller' => 'wild_posts'),
            'Pages' => array('controller' => 'wild_pages'),
            'Files' => array('controller' => 'wild_assets'),
            'Accounts' => array('controller' => 'wild_users'),
            'Settings' => array('controller' => 'wild_settings')
        ), array('id' => 'nav'));
    ?>
</div>

<div id="whiteness">
    <div id="wrap">
        <?php echo $content_for_layout ?>
        <div class="cleaner"></div>
    </div>
</div>

<p id="footer">
    <?php echo $html->link('Powered by Wildflower', array('controller' => 'wild_pages', 'action' => 'wf_about')) ?> &bull; 
    <?php echo $html->link('Icons by DryIcons', 'http://dryicons.com') ?>
</p>

</body>
</html>

