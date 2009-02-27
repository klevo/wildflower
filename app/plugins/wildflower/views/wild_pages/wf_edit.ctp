<?php 
    $partialLayout->switchToEditorMode();
    $partialLayout->setLayoutVar('publishedLink', $html->link(FULL_BASE_URL . $this->base . $this->data['WildPage']['url'], $this->data['WildPage']['url']));
    $session->flash();
    
    echo 
    $form->create('WildPage', array('url' => $html->url(array('action' => 'wf_update', 'base' => false)), 'class' => 'editor_form'));
?>

<?php
    echo
    $form->input('title', array('between' => '', 'label' => 'Page title')), 
    $form->input('content', array(
        'type' => 'textarea',
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
    <li>
        <h4>Text formatting</h4>
        <p>Use simple words or codes to format the text.</p>
        <p>You can insert HTML code (like a YouTube video) right into the editor.</p>
        <table>
            <thead>
                <tr><th><code>You type</code></th><th>Result</th></tr>
            </thead>
            <tbody>
                <tr><td><code>*A big cat*</code></td><td><?php echo $textile->format('*A big cat*'); ?></td></tr>
                <tr><td><code>_really_</code></td><td><?php echo $textile->format('_really_'); ?></td></tr>
            </tbody>
        </table>
        
        <a href="#MoreFormating">More formatting options</a>
    </li>
    
    <li class="main_sidebar">
        <ul class="sidebar-menu-alt edit-sections-menu">
            <li><?php echo $html->link('Options <small>like status, publish date, etc.</small>', array('action' => 'options', $this->data['WildPage']['id']), array('escape' => false)); ?></li>
            <li><?php echo $html->link('Browse older versions', array('action' => 'versions', $this->data['WildPage']['id'])); ?></li>
        </ul>
    </li>
<?php $partialLayout->blockEnd(); ?>
