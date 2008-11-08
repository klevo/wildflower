<?php 
echo $navigation->create(array(
        'Title and content' => array('action' => 'edit', $this->data['Post']['id']),
        'Categories' => '#Categories',
        'Options' => '#Options',
        'Preview' => '#Preview',
        'Revisions' => '#Revisions',
		'View' => Post::getUrl($this->data['Post']['slug']),
        'All posts' => array('action' => 'index'),
    ), array('id' => 'sub-nav')); 
    
echo $form->create('Post', array('action' => 'admin_update')); 
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
        'type' => 'textarea',
        'tabindex' => '2',
        'class' => 'fck',
        'rows' => '25',
    		'label' => __('Post', true),
        'div' => array('class' => 'input editor')));
?>

<div id="post-categories">
	<?php echo $this->element('post_category_select') ?>
</div>

<div id="advanced-options">
<?php
    echo 
    $form->input('draft', array('type' => 'select', 'between' => '<br />', 'label' => __('Status', true), 'options' => Post::getStatusOptions())),
    $form->input('description_meta_tag', array('label' => __('Description Meta Tag', true), 'between' => '<br />', 'type' => 'textarea', 'rows' => 6, 'cols' => 27, 'tabindex' => '4')),
    $form->input('slug', array('between' => '<br />', 'label' => __('URL slug', true), 'size' => 30)),
    $form->input('created', array('between' => '<br />', 'label' => __('Created', true)));
?>
    
    <!-- <p><?php echo $html->link(__('Delete this post', true), 
                array('action' => 'delete', $this->data['Post']['id']), 
                array('tabindex' => '7', 'class' => 'delete-one', 'rel' => 'post')); ?></p>  -->
</div>

<div id="sidebar-editor">
    <?php
        echo $form->input('sidebar_content', array(
        		'label' => __('Sidebar content', true),
            'type' => 'textarea',
            'class' => 'fck',
            'div' => array('class' => 'input editor')));
    ?>
</div>

<?php echo $this->element('admin_revision_list') ?>

<div class="big-submit">
    <?php if ($isDraft) { ?>
	<button id="publish" type="submit" tabindex="3" name="data[Post][publish]"><span class="bl1"><span class="bl2">Publish</span></span></button>
    <?php } ?>
    <?php if ($isRevision) { ?>
<<<<<<< .mine
    <input type="submit" value="<?php echo__('Save as current version') ?>" tabindex="3" />
=======
	<button type="submit" tabindex="3"><span class="bl1"><span class="bl2">Save as current version</span></span></button>
>>>>>>> .theirs
    <?php } else { ?>
	<button id="save" type="submit" tabindex="3"><span class="bl1"><span class="bl2">Save</span></span></button>
    <?php } ?>
    <p id="save-info">
        <?php echo __('This post was last saved') ?> <abbr id="modified-time" title="<?php echo $time->nice($this->data['Post']['updated']) ?>"><?php echo $time->niceShort($this->data['Post']['updated']), '</abbr>'; if ($hasUser) { ?> 
        <?php echo sprintf(__('by %s', true), hsc($this->data['User']['name'])); } ?>. 
    </p>
</div>

<div>
<?php
    echo $form->hidden('id');
    echo $form->hidden('url');
?>
</div>
<?php echo $form->end(); ?>
