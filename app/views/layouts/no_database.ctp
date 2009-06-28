<?php echo $html->doctype('xhtml-strict') ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <?php echo $html->charset(); ?>
    
    <title>[!] Database error &bull; Wildflower CMS</title>
    
    <link rel="icon" href="<?php echo $this->webroot;?>favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="<?php echo $this->webroot;?>favicon.ico" type="image/x-icon" />
    
    <?php 
        echo $html->css(array('admin/main'));
    ?>
        
    <!--[if lte IE 6]>
    <?php echo $html->css('ie6'); ?>
    <![endif]-->
    <!--[if lte IE 7]>
    <?php echo $html->css('ie67'); ?>
    <![endif]-->
</head>
<body>

<div id="header">
    <div class="container">
  
    <h1><?php echo $html->link('Wildflower CMS', '/') ?></h1>
    
    <span class="cleaner"> </span>
    
    <hr />
        
    </div>
</div>
    

<div id="wrap">
        
    <div id="ws"><div id="wsb"><div id="wsr"><div id="wslb"><div id="wsrt"><div id="wsrb">
        <div id="content">
            <?php echo $content_for_layout; ?>
        </div>
    </div></div></div></div></div></div>
    
</div>

</body>
</html>

