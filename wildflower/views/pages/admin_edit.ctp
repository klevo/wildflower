<?php 
echo $navigation->create(array(
        'Title and content' => array('action' => 'edit', $this->data['Page']['id']),
        'Sidebar' => '#Sidebar',
        'Options' => '#Options',
        'Preview' => '#Preview',
        'Revisions' => '#Revisions',
				'View' => $this->data['Page']['url'],
        'All pages' => array('action' => 'index'),
    ), array('id' => 'sub-nav')); 
    
echo $form->create('Page', array('action' => 'admin_update')); 
?>

<?php
    if ($isRevision) {
        echo '<h2 class="top revision-h"><span>' . __('Revision') .' #', $revisionId, ', ' . __('saved') . ', ' . $time->niceShort($revisionCreated) . ',</span></h2>';
    }
    
    echo 
    $form->input('title', array(
    		'label' => __('Title', true),
        'between' => '<br />',
        'tabindex' => '1',
        'div' => array('class' => 'input title-input'))),
    $form->input('content', array(
    	'label' => __('Page content', true),
    	'type' => 'textarea',
    	'tabindex' => '2',
    	'class' => 'fck',
    	'rows' => '25',
    	'div' => array('class' => 'input editor')));
?>

<div id="advanced-options">
<?php
    echo
    $this->element('parent_pages_select'),
    $form->input('draft', array('type' => 'select', 'between' => '<br />', 'label' => __('Status', true), 'options' => Page::getStatusOptions())),
    $form->input('description_meta_tag', array('label' => __('Description Meta Tag', true), 'between' => '<br />', 'type' => 'textarea', 'rows' => 6, 'cols' => 27, 'tabindex' => '4')),
    $form->input('slug', array('between' => '<br />', 'label' => __('URL slug', true), 'size' => 30)),
    $form->input('created', array('label' => __('Created', true), 'between' => '<br />'));
?>
    
    <!-- <p><?php echo $html->link(__('Delete this page', true), 
                array('action' => 'delete', $this->data['Page']['id']), 
                array('tabindex' => '7', 'class' => 'delete-one', 'rel' => 'page')); ?></p>  -->
</div>

<div id="sidebar-editor">
    <?php
        echo $form->input('sidebar_content', array(
            'type' => 'textarea',
            'class' => 'fck',
            'label' => __('Sidebar content', true),
            'div' => array('class' => 'input editor')));
    ?>
</div>

<?php echo $this->element('admin_revision_list') ?>

<div class="big-submit">
    <?php if ($isDraft) { ?>
	<button id="publish" type="submit" tabindex="3" name="data[Page][publish]"><span class="bl1"><span class="bl2">Publish</span></span></button>
    <?php } ?>
    <?php if ($isRevision) { ?>
	<button type="submit" tabindex="3"><span class="bl1"><span class="bl2"><?php __('Save as current version') ?></span></span></button>
    <?php } else { ?>
	<button id="save" type="submit" tabindex="3"><span class="bl1"><span class="bl2">Save</span></span></button>
    <?php } ?>
    <p id="save-info">
        <?php echo __('This page was last saved') ?> <abbr id="modified-time" title="<?php echo $time->nice($this->data['Page']['updated']) ?>"><?php echo $time->niceShort($this->data['Page']['updated']), '</abbr>'; if ($hasUser) { ?> 
        <?php printf(__('by %s', true), hsc($this->data['User']['name'])); } ?>. 
    </p>
</div>

<div>
<?php
    echo $form->hidden('id');
    echo $form->hidden('url');
?>
</div>
<?php echo $form->end(); ?>
