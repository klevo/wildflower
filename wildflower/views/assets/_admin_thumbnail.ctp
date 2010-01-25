
            
            <a class="thumbnail" href="<?php echo $html->url(array('action' => 'edit', $file['Asset']['id'])); ?>">
    	        <img width="50" height="50" src="<?php echo $html->url("/wildflower/thumbnail/{$file['Asset']['name']}/50/50/1"); ?>" alt="<?php echo hsc($file['Asset']['title']); ?>" />
    	    </a>