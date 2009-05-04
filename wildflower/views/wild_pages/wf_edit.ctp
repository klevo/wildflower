<?php 
    if ($session->check('Message.flash')) {
        $session->flash();
    }
    
    echo 
    $form->create('WildPage', array('url' => $html->url(array('action' => 'wf_update', 'base' => false)), 'class' => 'editor_form')),
    $form->input('title', array('between' => '', 'label' => 'Page title')), 
    $form->input('content', array(
        'type' => 'textarea',
        'class' => 'tinymce',
        'rows' => 25,
        'cols' => 60,
        'label' => 'Body',
        'div' => array('class' => 'input editor'))),
    '<div>',
    $form->hidden('id'),
    $form->hidden('draft'),
    '</div>';
?>

<div id="edit-buttons">
    <?php echo $this->element('wf_edit_buttons'); ?>
</div>

<?php 
    echo 
    
    // Options for create new JS
	$form->input('parent_id_options', array('type' => 'select', 'options' => $newParentPageOptions, 'empty' => '(none)', 'div' => array('class' => 'all-page-parents input select'), 'label' => __('Parent page', true), 'escape' => false)),
	
	$form->end();
?>

<span class="cleaner"></span>

<?php $partialLayout->blockStart('sidebar'); ?>
    <?php echo $this->element('../wild_pages/_page_edit_right_menu'); ?>
<?php $partialLayout->blockEnd(); ?>
