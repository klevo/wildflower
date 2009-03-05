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
        )),
        // TinyMCE 
        // @TODO load only on pages with editor?
        $javascript->link('/wildflower/js/tiny_mce/tiny_mce');
    ?>
     
    <!--[if lte IE 7]>
    <?php
        // CSS file for Microsoft Internet Explorer 7 and lower
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
            prefix: '<?php echo Configure::read('Wildflower.prefix') ?>',
            custom: {
                wildflowerUploads: '<?php echo Configure::read('Wildflower.uploadsDirectoryName'); ?>'
            }
        });
        
        tinyMCE.init($.jlm.components.tinyMce.getConfig());

        $(function() {
           $.jlm.dispatch(); 
        });
    //]]>
    </script>
    
</head>
<body>

<?php if (!isset($editorMode)): ?>    
<div id="admin_bar">
    <?php echo $html->link('View homepage', '/'); ?>
</div>

<div id="header">
    <div id="header-wrap">
        <h1 id="site-title"><?php echo hsc($siteName); ?></h1>
        
        <div id="login-info">
            <?php echo $htmla->link(__('Settings', true), array('controller' => 'wild_settings')); ?> | 
            <?php echo $htmla->link(__('Users', true), array('controller' => 'wild_users')); ?> | 
            <?php echo $htmla->link(__('Logout', true), array('controller' => 'wild_users', 'action' => 'logout'), array('id' => 'logout')); ?>
            
        </div>

        <?php 
            echo $navigation->create(array(
                __('Dashboard', true) => '/' . Configure::read('Wildflower.prefix'),
                __('Pages', true) => array('controller' => 'wild_pages'),
                __('Blog', true) => array('controller' => 'wild_posts'),
                __('Files', true) => array('controller' => 'wild_assets'),
            ), array('id' => 'nav'));
        ?>
    </div>
</div>
<?php else: ?>
<ul id="editor_mode_header">
    <li><?php echo $html->link('Go to all pages', array('action' => 'index')); ?></li>
    <li><small>(Published at: <?php if (isset($publishedLink)) echo $publishedLink; ?>)</small></li>
</ul>
<?php endif; ?>

<div id="wrap">
    <div id="content">
        <div id="co_top_shadow">
        <div id="co_top_right_corner">
        <div id="co_bottom_shadow">
        <div id="co_right_shadow">
        <div id="co_right_bottom_corner">
        <div id="content-pad">
            <?php echo $content_for_layout; ?>
        </div>
        </div>
        </div>
        </div>
        </div>
        </div>
    </div>
    
    <?php if (isset($sidebar_for_layout)): ?>
    <div id="sidebar">
        <ul>
            <?php echo $sidebar_for_layout; ?>
        </ul>
    </div>
    <?php endif; ?>
        
    <div class="cleaner"></div>
</div>

<p id="footer">
    <?php echo $html->link(__('Powered by Wildflower', true), array('controller' => 'wild_pages', 'action' => 'wf_about')); ?>&nbsp;&nbsp;
    <?php if (Configure::read('debug') > 0) echo __('Debug mode'), ' ', Configure::read('debug'); ?>
</p>

</body>
</html>

