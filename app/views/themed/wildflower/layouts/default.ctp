<?php echo $html->doctype('xhtml-strict') ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <?php echo $html->charset(); ?>
    
    <title><?php echo $title_for_layout; ?></title>
    
    <meta name="description" content="<?php echo isset($descriptionMetaTag) ? $descriptionMetaTag : '' ?>" />
    <meta name="keywords" content="<?php echo isset($keywordsMetaTag) ? $keywordsMetaTag : '' ?>" />
    
    <link rel="shortcut icon" href="<?php echo $this->webroot;?>favicon.ico" type="image/x-icon" />
    <link rel="alternate" type="application/rss+xml" title="<?php echo $siteName; ?> RSS Feed" href="<?php echo $html->url('/' . Configure::read('Wildflower.blogIndex') . '/rss'); ?>" />
    
    <base href="<?php echo FULL_BASE_URL . rtrim($this->here, '/') . '/'; ?>" />
    
    <?php echo $html->css(array('wildflower/main')); ?>
        
    <script type="text/javascript">
        BASE = '<?php echo $this->base ?>';
    </script>
</head>
<body>
    
    <?php
        // @TODO Refactor to a Helper
        
        // Admin bar
        // Do not show for previews
        if (($isLogged or Configure::read('debug') > 0) and $this->params['action'] != 'wf_preview') {
            $c = str_replace('wild_', '', $this->params['controller']);
            if (isset($page['WildPage']['id'])) {
                $id = $page['WildPage']['id'];
            } else if (isset($post['WildPost']['id'])) {
                $id = $post['WildPost']['id'];
            }
            
            if (isset($id)) {
                $editCurrentLink = '/' 
                    . Configure::read('Wildflower.prefix') 
                    . '/' . $c . '/edit/' . $id;
            } else {
                $editCurrentLink = '/' 
                    . Configure::read('Wildflower.prefix') 
                    . '/' . $c;
            }

            
            echo 
            '<div id="admin_bar">',
                $html->link('Site admin', '/' . Configure::read('Wildflower.prefix')),
                $html->link('Edit current page', $editCurrentLink),
             '</div>';
        }
    ?>

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
            ), array('id' => 'navigation'));
        ?>
        
        <hr />
        
        <div id="content">
            <?php echo $content_for_layout; ?>
            <span class="cleaner">&nbsp;</span>
        </div>
        <div class="clear"></div>
        <hr />
        
        <div id="footer">
	        <p>Powered by <?php 
	           echo $html->image('wildflower/small-logo.gif', array('alt' => 'Wildflower', 'class' => 'wf-icon')), 
	           ' ', 
	           $html->link('Wildflower', 'http://wf.klevo.sk/'),
	           '. ',
	           $this->element('admin_link'); ?></p>
	        
	        <?php echo $this->element('debug_notice'); ?>
	    </div>
        
    </div>
    
    <?php echo $this->element('google_analytics'); ?>
    
</body>
</html>
