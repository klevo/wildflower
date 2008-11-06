<?php 
echo $navigation->create(array(
        'Title and content' => array('action' => 'wf_edit', $this->data['WildPage']['id']),
        'Sidebar' => '#Sidebar',
        'Options' => '#Options',
        'Preview' => '#Preview',
        'Revisions' => '#Revisions',
		'View' => $this->data['WildPage']['url'],
        'All pages' => array('action' => 'index'),
    ), array('id' => 'sub-nav')); 
    
echo $form->create('WildPage', array('url' => $html->url(array('action' => 'wf_update', 'base' => false)))); 
?>

<?php
    if ($isRevision) {
        echo '<h2 class="top revision-h"><span>Revision #', $revisionId, ', saved ', $time->niceShort($revisionCreated), '</span></h2>';
    }
    
    echo 
    $form->input('title', array(
        'between' => '<br />',
        'tabindex' => '1',
        'div' => array('class' => 'input title-input'))),
    $form->input('content', array(
		'between' => '<br />',
    	'type' => 'textarea',
    	'tabindex' => '2',
    	'class' => 'fck',
    	'rows' => '25',
        'label' => 'Page content',
    	'div' => array('class' => 'input editor')));
?>

<div id="advanced-options">
<?php
    echo
    $this->element('parent_pages_select'),
    $form->input('draft', array('type' => 'select', 'between' => '<br />', 'label' => 'Status', 'options' => WildPage::getStatusOptions())),
    $form->input('description_meta_tag', array('between' => '<br />', 'type' => 'textarea', 'rows' => 6, 'cols' => 27, 'tabindex' => '4')),
    $form->input('slug', array('between' => '<br />', 'label' => 'URL slug', 'size' => 30)),
    $form->input('created', array('between' => '<br />'));
?>
    
    <!-- <p><?php echo $html->link('Delete this page', 
                array('action' => 'delete', $this->data['WildPage']['id']), 
                array('tabindex' => '7', 'class' => 'delete-one', 'rel' => 'page')); ?></p>  -->
</div>

<div id="sidebar-editor">
    <?php
        echo $form->input('sidebar_content', array(
            'type' => 'textarea',
            'class' => 'fck',
            'label' => 'Sidebar content',
            'div' => array('class' => 'input editor')));
    ?>
</div>

<?php echo $this->element('admin_revision_list') ?>

<div class="big-submit">
    <?php if ($isDraft) { ?>
	<button id="publish" type="submit" tabindex="3" name="data[WildPage][publish]"><span class="bl1"><span class="bl2">Publish</span></span></button>
    <?php } ?>
    <?php if ($isRevision) { ?>
	<button type="submit" tabindex="3"><span class="bl1"><span class="bl2">Save as current version</span></span></button>
    <?php } else { ?>
	<button id="save" type="submit" tabindex="3"><span class="bl1"><span class="bl2">Save</span></span></button>
    <?php } ?>
    <p id="save-info">
        This page was last saved <abbr id="modified-time" title="<?php echo $time->nice($this->data['WildPage']['updated']) ?>"><?php echo $time->niceShort($this->data['WildPage']['updated']), '</abbr>'; if ($hasUser) { ?> 
        by <?php echo hsc($this->data['WildUser']['name']); } ?>. 
    </p>
</div>

<div>
<?php
    echo $form->hidden('id');
    echo $form->hidden('url');
?>
</div>
<?php echo $form->end(); ?>
