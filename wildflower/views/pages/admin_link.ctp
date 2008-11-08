<h2 class="top">Insert Link <a href="#Close" class="close">Close</a></h2>

<?php echo $form->create('Link') ?>

	<?php echo $form->input('location', array('value' => 'http://', 'label' => __('Type the URL', true) . ' ', 'size' => '30')) ?>
	
	<p>or</p>
	
	<?php 
	    echo $form->label('page', __('select a page', true) . ' '); 
	    echo $form->select('page', $pages, null, null, true);    
	?>
	<div id="pageUrlMap">    
	<?php 
	    // Pages URL map
	    foreach ($pageUrlMap as $id => $url) {
	    	echo "<span class=\"page-$id\">", $html->url($url), "</span>";
	    }
	?>    
	</div>
	    
	<p><?php echo __('or') ?></p>
	
	<?php 
	    echo $form->label('post', __('a post', true) . ' '); 
	    echo $form->select('post', $posts, null, null, true);    
	?>
	<div id="postUrlMap">    
	<?php 
	    // Pages URL map
	    foreach ($postUrlMap as $id => $url) {
	        echo "<span class=\"post-$id\">", $html->url($url), "</span>";
	    }
	?>    
	</div>

	<div class="submit form-bottom">
	    <input type="submit" value="Insert" tabindex="">
	    <span class="edit-actions"> <?php echo __('or') ?> 
	        <?php echo $html->link(__('Cancel', true), '#Cancel', array('tabindex' => '', 'class' => 'cancel')); ?></span>
	</div>
	
<?php echo $form->end() ?>
