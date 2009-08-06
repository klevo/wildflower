<h2 class="section"><?php __('Pages'); ?></h2>

<?php
	echo 
	$form->create('Page', array('action' => 'admin_mass_update'));
?>

<?php
    echo 
    $this->element('admin_select_actions'),
    $tree->generate($pages, array('model' => 'Page', 'class' => 'list pages-list', 'element' => 'admin_page_list_item')),
    $this->element('admin_select_actions'),
    
    // Options for create new JS
	$form->input('parent_id_options', array('type' => 'select', 'options' => $newParentPageOptions, 'empty' => '(none)', 'div' => array('class' => 'all-page-parents input select'), 'label' => __('Parent page', true), 'escape' => false)),
    
    $form->end();
?>


<?php $partialLayout->blockStart('sidebar'); ?>
    <li>
        <?php echo $this->element('../pages/_sidebar_search'); ?>
    </li>
    <li>
        <?php echo $html->link(
            '<span>' . __('Create a new page', true) . '</span>', 
            array('action' => 'admin_create'),
            array('class' => 'add', 'escape' => false)) ?>
    </li>
<?php $partialLayout->blockEnd(); ?>

