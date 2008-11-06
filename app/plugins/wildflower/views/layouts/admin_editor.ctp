<?php echo $html->doctype('xhtml-strict') ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <?php echo $html->charset(); ?>
	
	<title><?php echo $title_for_layout; ?></title>
	
	<meta name="description" content="" />
	
    <link rel="icon" href="<?php echo $this->webroot;?>favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="<?php echo $this->webroot;?>favicon.ico" type="image/x-icon" />
	
	<?php 
        echo $html->css(array('wfadmin/main')),
             $javascript->link(array('tiny_mce/tiny_mce'));
    ?>
    
    <?php echo $this->element('tiny_mce') ?>
    
    <!-- JQuery Light MVC -->
    <script type="text/javascript">
    //<![CDATA[
        BASE = '<?php echo $this->base ?>';
        CONTROLLER = '<?php echo $this->params['controller'] ?>';
        MODEL = '<?php echo ucwords(Inflector::singularize($this->params['controller'])) ?>';
        ACTION = '<?php echo $this->params['action'] ?>';
    //]]>
    </script>
    <script type="text/javascript" src="<?php echo $html->url('/jlm/packager.php?time=' . time()); ?>"></script>
</head>
<body>

    <div id="header">
        <h1 id="site-title">
            <?php echo $html->link($siteName, '/', array('title' => 'View site home page')) ?>
        </h1>
        
        <div id="login-info">
            <span id="login-info-user">Logged in as <?php echo $html->link($loggedUserName, array('controller' => 'users', 'action' => 'edit', 'id' => $loggedUserId)) ?></span>
            <?php echo $html->link('Log out', '/users/logout', array('id' => 'logout')); ?>
        </div>
    </div>



    <?php echo $content_for_layout ?>


</body>
</html>
