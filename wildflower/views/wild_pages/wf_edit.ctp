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
    <li class="versions main_sidebar">
        <h4>Versions</h4>
        <ul>
        <?php
            $attr = array();
            foreach ($revisions as $version) {
                if (isset($this->params['named']['rev']) and $this->params['named']['rev'] == $version['WildRevision']['revision_number']) {
                    $attr['class'] = 'current';
                }
                echo '<li>', $html->link($time->niceShort($version['WildRevision']['created']), "/{$this->params['prefix']}/pages/edit/{$this->data['WildPage']['id']}/rev:{$version['WildRevision']['revision_number']}", $attr), '</li>';
                $attr['class'] = '';
            }
        ?>
        </ul>
    </li>
    
    <li class="main_sidebar">
        
        <ul class="sidebar-menu-alt edit-sections-menu">
            <li><?php echo $html->link('Options <small>like status, publish date, etc.</small>', array('action' => 'options', $this->data['WildPage']['id']), array('escape' => false)); ?></li>
            <li><?php echo $html->link('Sidebar', array('action' => 'sidebar', $this->data['WildPage']['id']), array('escape' => false)); ?></li>
        </ul>
    </li>
<?php $partialLayout->blockEnd(); ?>
