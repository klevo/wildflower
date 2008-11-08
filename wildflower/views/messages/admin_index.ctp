<?php
    echo $navigation->create(array(
	        'Delete' => '#Delete', 
	    ), array('id' => 'list-toolbar'));

    echo $list->create($messages, array('model' => 'Message', 'class' => 'list selectable-list', 'element' => 'admin_messages_list_item'));
?>

<div class="paginator">
	<?php echo $paginator->counter(array(
		'format' => __('%start% to %end% of %count%',true)
	)); ?>
</div>
    
