<?php echo $html->doctype('xhtml-strict') ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <?php echo $html->charset(); ?>
	
	<title><?php echo $title_for_layout; ?></title>
	
	<meta name="description" content="" />
	
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
        
        tinyMCE.init($.jlm.components.tinyMce.getConfig());

        $(function() {
           $.jlm.dispatch(); 
        });
    //]]>
    </script>
    
</head>
<body>


<div id="header">
    <div id="header-wrap">
        <h1 id="site-title">
            <?php echo $html->link($siteName, '/', array('title' => __('View site home page', true))) ?>
        </h1>
    
        <div id="login-info">
            <?php echo $html->link(__('Log out'), array('controller' => 'wild_users', 'action' => 'logout'), array('id' => 'logout')); ?>
        </div>
    
        <?php 
            echo $navigation->create(array(
                __('Dashboard', true) => '/' . Configure::read('Wildflower.prefix'),
                __('Blog', true) => array('controller' => 'wild_posts'),
                __('Pages', true) => array('controller' => 'wild_pages'),
                __('Files', true) => array('controller' => 'wild_assets'),
                __('Accounts', true) => array('controller' => 'wild_users'),
                __('Settings', true) => array('controller' => 'wild_settings')
            ), array('id' => 'nav'));
        ?>
    </div>
</div>

<div id="whiteness">
    <div id="wrap">
        <div id="content">
            <div id="content-pad">
                <?php echo $content_for_layout; ?>
            </div>
        </div>
        
        <div id="sidebar">
            <ul>
                <?php if (isset($sidebar_for_layout)) echo $sidebar_for_layout; ?>
            </ul>
        </div>
            
        <div class="cleaner"></div>
    </div>
</div>

<p id="footer">
    <?php echo $html->link(__('Powered by Wildflower', true), array('controller' => 'wild_pages', 'action' => 'wf_about')) ?> &bull; 
    <?php echo $html->link(__('Icons by DryIcons', true), 'http://dryicons.com') ?>
</p>

</body>
</html>

