<?php
	echo 
	$form->create('Page', array('url' => $html->url(array('action' => 'wf_mass_update', 'base' => false))));
?>

<h2 class="section">Site Pages</h2>

<?php
    echo 
    $this->element('wf_select_actions'),
    $tree->generate($pages, array('model' => 'WildPage', 'class' => 'list pages-list', 'element' => 'wf_page_list_item')),
    $this->element('wf_select_actions'),
    
    // Options for create new JS
	$form->input('parent_id_options', array('type' => 'select', 'options' => $newParentPageOptions, 'empty' => '(none)', 'div' => array('class' => 'all-page-parents input select'), 'label' => __('Parent page', true), 'escape' => false)),
    
    $form->end();
?>


<?php $partialLayout->blockStart('sidebar'); ?>
    <li>
        <?php echo $html->link(
            '<span>' . __('Write a new page', true) . '</span>', 
            array('action' => 'wf_create'),
            array('class' => 'add', 'escape' => false)) ?>
    </li>
    <li>
        <?php echo $this->element('../wild_pages/_sidebar_search'); ?>
    </li>
<?php $partialLayout->blockEnd(); ?>

