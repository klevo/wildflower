<div id="content">
<?php
	echo 
	$form->create('Page', array('url' => $here));
?>

<h2 class="section">Site Pages</h2>

<?php
    echo $tree->generate($pages, array('model' => 'WildPage', 'class' => 'list pages-list', 'element' => 'wf_page_list_item'));
?>

<?php
    echo 
    $form->input('action', array(
	    'type' => 'select',
	    'options' => array(
	        'choose' => '(choose an action)',
	        'publish' => 'Publish',
	        'draft' => 'Unpublish',
	        'delete' => 'Delete',
	    ),
	    'label' => 'With selected '
	)),
    $form->end();
?>
</div>

<ul id="sidebar">
    <li>
        <?php echo $html->link(
            '<span>' . __('Write a new page', true) . '</span>', 
            array('action' => 'wf_create'),
            array('class' => 'add', 'escape' => false)) ?>
    </li>
    <li>
        <?php
            echo
            $form->create('WildPage', array('action' => 'search', 'class' => 'search')),
            $form->input('query', array('label' => __('Find a page by typing', true))),
            $form->end();
        ?>
    </li>
</ul>

