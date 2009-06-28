<div id="home_slider" class="slider">
    <a class="prev"></a>

    <div class="scrollable">
    	<ul id="slider_<?php echo $data['WildWidget']['id']; ?>" class="thumbs">
    	    <?php
    	        if ($data['WildWidget']['randomize']) {
    	            function fisherYatesShuffle(&$items) {
                       for ($i = count($items) - 1; $i > 0; $i--) {
                          $j = @mt_rand(0, $i);
                          $tmp = $items[$i];
                          $items[$i] = $items[$j];
                          $items[$j] = $tmp;
                       }
                    }
    	            fisherYatesShuffle($data['WildWidget']['items']);
    	            fb($data['WildWidget']['items']);
    	        }
    	        foreach ($data['WildWidget']['items'] as $item) {
    	            $cell = $html->link($item['label'], $item['url']);
    	            if (empty($item['url'])) {
    	                $cell = '<span class="no_link">' . hsc($item['label']) . '</span>';
    	            }
    	            echo '<li>', $cell, '</li>';
    	        }
    	    ?>
        </ul>
    </div>

    <a class="next"></a>
</div>


<script type="text/javascript">
$(function() {		
	// initialize scrollable 
	$(".scrollable").scrollable({
		size: 3,
		items: '#slider_<?php echo $data['WildWidget']['id']; ?>',  
		hoverClass: 'hover'
	});	
});
</script>