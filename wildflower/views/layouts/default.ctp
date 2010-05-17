<?php echo $html->doctype('xhtml-strict'); ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <?php echo $html->charset(); ?>
    
	<!-- aiming to show theme cake version and wf version when in debug mode -->
    
	<?php echo
			$wild->title($title_for_layout),
			$wild->meta(),
			$wild->icon(),
			$wild->rss(array('title' => $siteName)),
			$html->css('wfsite'); ?>
</head>
<body>
<div id="wrap">

    <div id="header">   
        <h1><?php echo $html->link("<span>$title_for_layout</span>", '/', array('escape' => false), null) ?></h1>
        <?php echo
				$html->image('/wildflower/img/logo.jpg'),
				$wild->rss(array('class' => 'rss', 'title' => $siteName, 'display' => 'rss')); ?>
    </div>
    
    <hr />
    
    <?php echo 
				$wild->menu('main_menu'),
				$this->element('latest_posts', array('categorySlug'=>'action','categoryLimit'=>4)),
				$wild->search();	?>
    
    <hr />
    
    <div id="content">
        <?php echo $content_for_layout; ?>
        <span class="cleaner">&nbsp;</span>
    </div>
    
    <hr />
    
    <div id="footer">
        <p><?php echo isset($credits)? $credits : ''; echo $this->element('admin_link'); ?></p>
        
        <?php echo $this->element('debug_notice'), $this->element('sql_dump');; ?>
    </div>
    
</div>
<?php echo $this->element('google_analytics'); ?>
</body>
</html>

