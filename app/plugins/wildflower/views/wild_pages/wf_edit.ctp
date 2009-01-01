<?php 
    $session->flash();
    
    echo 
    $form->create('WildPage', array('url' => $html->url(array('action' => 'wf_update', 'base' => false)), 'class' => 'editor-form'));
?>

<div id="title-content">
    <?php
        echo
        $form->input('title', array(
            'between' => '<br />',
            'tabindex' => '1',
            'label' => __('Page title', true),
            'div' => array('class' => 'input title-input'))),
        $form->input('content', array(
            'type' => 'textarea',
            'tabindex' => '2',
            'class' => 'tinymce',
            'rows' => '25',
            'label' => __('Body', true),
            'div' => array('class' => 'input editor'))),
        '<div>',
        $form->hidden('id'),
        '</div>';
    ?>
    
    <div id="edit-buttons">
        <?php echo $this->element('wf_edit_buttons'); ?>
    </div>
</div>

<div id="post-options">
    <h2 class="section">Page Options</h2>
    <?php
        echo 
        $form->input('draft', array('type' => 'select', 'between' => '<br />', 'label' => 'Status', 'options' => WildPage::getStatusOptions())),
        $form->input('description_meta_tag', array('between' => '<br />', 'type' => 'textarea', 'rows' => 6, 'cols' => 27, 'tabindex' => '4')),
        //$form->input('slug', array('between' => '<br />', 'label' => 'URL slug', 'size' => 30)),
        $form->input('created', array('between' => '<br />'));
    ?>
    <div class="submit save-section">
        <input type="submit" value="<?php __('Save options'); ?>" />
    </div>
    <div class="cancel-edit cancel-section"> <?php __('or'); ?> <?php echo $html->link(__('Cancel and go back to post edit', true), '#Cancel'); ?></div>
</div>    

<div id="post-revisions">
    <h2 class="section">Older versions of this page</h2>
    <?php 
        if (!empty($revisions)) {
            echo 
            '<ul id="revisions" class="list revision-list">';

            $first = '<span class="current-revision">&mdash;current version</span>';
            foreach ($revisions as $version) {
                $attr = '';
                if (ListHelper::isOdd()) {
                    $attr = ' class="odd"';
                }
                echo 
                "<li$attr>",
                '<div class="list-item">',
                $html->link("Revision {$version['WildRevision']['revision_number']}",
                    array('action' => 'wf_edit', $version['WildRevision']['node_id'], $first ? null : $version['WildRevision']['revision_number']), null, null, false),
                "<small>$first, saved {$time->niceShort($version['WildRevision']['created'])} by {$version['WildUser']['name']}</small>",
                '</div>',
                '</li>';
                $first = '';
            }
            echo '</ul>';
        } else {
            echo "<p id=\"revisions\">No revisions yet.</p>";
        }
    ?>        
</div>

<?php 
    echo 
    
    // Options for create new JS
	$form->input('parent_id_options', array('type' => 'select', 'options' => $newParentPageOptions, 'empty' => '(none)', 'div' => array('class' => 'all-page-parents input select'), 'label' => __('Parent page', true), 'escape' => false)),
	
	$form->end();
	
	echo $this->element('../wild_pages/_page_info');
?>




<?php $partialLayout->blockStart('sidebar'); ?>
    <li>
        <?php echo $this->element('../wild_pages/_sidebar_search'); ?>
    </li>
    <li>
        <?php echo $html->link(
            '<span>Write a new page</span>', 
            array('action' => 'wf_create'),
            array('class' => 'add', 'escape' => false)); ?>
    </li>
    <li>
        <ul class="sidebar-menu-alt edit-sections-menu">
            <li><?php echo $html->link('Options <small>like status, publish date, etc.</small>', array('action' => 'options', $this->data['WildPage']['id']), array('escape' => false)); ?></li>
            <li><?php echo $html->link('Browse older versions', '#Revisions', array('rel' => 'post-revisions')); ?></li>
        </ul>
    </li>
    <li>
        <ul class="sidebar-menu">
            <li><?php echo $html->link(__('All Pages', true), array('action' => 'wf_index'), array('class' => 'back-to-all')); ?></li>
        </ul>
    </li>
<?php $partialLayout->blockEnd(); ?>
