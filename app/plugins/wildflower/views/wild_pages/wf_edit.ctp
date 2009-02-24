<?php 
    $partialLayout->setLayoutVar('isFullEdit', true);
    $session->flash();
    
    echo 
    $form->create('WildPage', array('url' => $html->url(array('action' => 'wf_update', 'base' => false)), 'class' => 'editor-form'));
?>

<h2 class="section title_as_heading"><?php echo hsc($this->data['WildPage']['title']); ?> <?php echo $html->link('Rename', '#Rename', array('class' => 'rename_title')); ?></h2>
<div class="section rename_title_section"><?php echo $form->input('title', array('between' => '<br />', 'label' => 'Page title', 'div' => array('class' => 'title_input'))), $form->submit('Rename'); ?></div>

<?php
    echo
    $form->input('content', array(
        'type' => 'textarea',
        'tabindex' => '2',
        'class' => 'tinymce',
        'rows' => '25',
        'label' => 'Page content',
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

<?php $partialLayout->blockStart('sidebar'); ?>
    <li class="main_sidebar">
        <?php echo $html->link(
            '<span>Write a new page</span>', 
            array('action' => 'wf_create'),
            array('class' => 'add', 'escape' => false)); ?>
    </li>
    <li class="main_sidebar">
        <ul class="sidebar-menu-alt edit-sections-menu">
            <li><?php echo $html->link('Options <small>like status, publish date, etc.</small>', array('action' => 'options', $this->data['WildPage']['id']), array('escape' => false)); ?></li>
            <li><?php echo $html->link('Browse older versions', array('action' => 'versions', $this->data['WildPage']['id'])); ?></li>
        </ul>
    </li>
    <li class="sidebar-box post-info main_sidebar">
        <?php echo $this->element('../wild_pages/_page_info'); ?>
    </li>
    <li><?php echo $html->link('Go to all pages', array('action' => 'index')); ?></li>
<?php $partialLayout->blockEnd(); ?>
