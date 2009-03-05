<?php 
    $partialLayout->switchToEditorMode();
    $partialLayout->setLayoutVar('publishedLink', $html->link(FULL_BASE_URL . $this->base . $this->data['WildPage']['url'], $this->data['WildPage']['url']));
    $session->flash();
    
    echo 
    $form->create('WildPage', array('url' => $html->url(array('action' => 'wf_update', 'base' => false)), 'class' => 'editor_form'));
?>

<?php /* 
<ul id="page_inserts">
    <li><span>Insert into page: </span></li>
    <li><a href="#InsertLink">Link</a></li>
    <li><a href="#InsertImage">Image or file</a></li>
</ul>
*/?>

<?php
    echo
    $form->input('title', array('between' => '', 'label' => 'Page title')), 
    $form->input('content', array(
        'type' => 'textarea',
        'class' => 'tinymce',
        'rows' => 25,
        'cols' => 60,
        'label' => 'Body',
        'div' => array('class' => 'input editor'))),
    $form->input('sidebar_content', array(
        'type' => 'textarea',
        'rows' => 25,
        'cols' => 60,
        'label' => 'Sidebar',
        'div' => array('class' => 'input sidebar_editor'))),
    '<div>',
    $form->hidden('id'),
    $form->hidden('draft'),
    '</div>';
?>

<p><a href="#ShowSidebarEditor">Show sidebar editor</a></p>

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
    <li class="texy_syntax">
        <h4>Text formatting</h4>
        <p>Use simple words or codes to format the text.</p>
        <p>You can insert HTML code (like a YouTube video) right into the editor.</p>
        <table class="texy_table">
            <thead>
                <tr><th>You type</th><th>Result</th></tr>
            </thead>
            <tbody>
                <tr><td><code>*emphasis*</code></td><td><?php echo $texy->process('*emphasis*'); ?></td></tr>
                <tr><td><code>**bold words**</code></td><td><?php echo $texy->process('**bold words**'); ?></td></tr>
                <tr><td><code>Heading<br />=======</code></td><td><?php echo $texy->process("Heading\n======="); ?></td></tr>
            </tbody>
        </table>
        
        <p><a href="#MoreFormating">More formatting options</a></p>
    </li>
<?php $partialLayout->blockEnd(); ?>
