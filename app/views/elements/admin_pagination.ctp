<div class="paginator">
	<?php
	    echo
	    '<div class="paginate-counter">', 
	    $paginator->counter(array(
    		'format' => '%start% to %end% of %count%'
    	)),
    	'</div>',
    	$paginator->prev('« Newer ', array('class' => 'paginate-prev'), null, array('class' => 'paginate-prev disabled')),
    	'<div class="paginate-numbers">',
    	$paginator->numbers(),
    	'</div>',
        $paginator->next(' Older »', array('class' => 'paginate-next'), null, array('class' => 'paginate-next disabled'));
    ?>
	<span class="cleaner"></span>
</div>