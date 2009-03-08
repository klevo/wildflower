<div class="slider">
    <a class="prev"></a>

    <div class="scrollable">
    	<ul id="slider1" class="thumbs">
    	    <?php
    	        foreach ($data['WildWidget']['items'] as $item) {
    	            echo '<li>', $html->link($item['label'], $item['url']), '</li>';
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
		items: '#slider1',  
		hoverClass: 'hover'
	});	
});
</script>