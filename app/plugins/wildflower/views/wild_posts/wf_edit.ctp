<div id="content">
    <?php 
    echo $form->create('WildPost', array('url' => $html->url(array('action' => 'wf_update', 'base' => false)))); 
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
            'type' => 'textarea',
            'tabindex' => '2',
            'class' => 'tinymce',
            'rows' => '25',
            'label' => 'WildPost',
            'div' => array('class' => 'input editor')));
    ?>

    <div id="advanced-options">
    <?php
        echo 
        $form->input('draft', array('type' => 'select', 'between' => '<br />', 'label' => 'Status', 'options' => WildPost::getStatusOptions())),
        $form->input('description_meta_tag', array('between' => '<br />', 'type' => 'textarea', 'rows' => 6, 'cols' => 27, 'tabindex' => '4')),
        $form->input('slug', array('between' => '<br />', 'label' => 'URL slug', 'size' => 30)),
        $form->input('created', array('between' => '<br />'));
    ?>

        <!-- <p><?php echo $html->link('Delete this post', 
                    array('action' => 'delete', $this->data['WildPost']['id']), 
                    array('tabindex' => '7', 'class' => 'delete-one', 'rel' => 'post')); ?></p>  -->
    </div>

    <div id="sidebar-editor">
        <?php
            echo $form->input('sidebar_content', array(
                'type' => 'textarea',
                'class' => 'fck',
                'div' => array('class' => 'input editor')));
        ?>
    </div>

    <?php echo $this->element('admin_revision_list') ?>

    <div class="big-submit">
        <?php if ($isDraft) { ?>
    	<button id="publish" type="submit" tabindex="3" name="data[WildPost][publish]"><span class="bl1"><span class="bl2">Publish</span></span></button>
        <?php } ?>
        <?php if ($isRevision) { ?>
    	<button type="submit" tabindex="3"><span class="bl1"><span class="bl2">Save as current version</span></span></button>
        <?php } else { ?>
    	<button id="save" type="submit" tabindex="3"><span class="bl1"><span class="bl2">Save</span></span></button>
        <?php } ?>
        <p id="save-info">
            This post was last saved <abbr id="modified-time" title="<?php echo $time->nice($this->data['WildPost']['updated']) ?>"><?php echo $time->niceShort($this->data['WildPost']['updated']), '</abbr>'; if ($hasUser) { ?> 
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
</div>


<ul id="sidebar">
    <li>
        <ul>
            <li><?php echo $html->link('Title & Content', array('action' => 'wf_edit')) ?></li>
            <li><?php echo $html->link('Categories', array('action' => 'wf_edit_categories')) ?></li>
        </ul>
    </li>
    <li><?php echo $html->link(
        '<span>Write a new post</span>', 
        array('action' => 'add'),
        array('class' => 'add', 'escape' => false)) ?></li>
    <li>
        <?php
            echo
            $form->create('WildPost'),
            $form->input('query', array('label' => __('Find a post by typing', true))),
            $form->end();
        ?>
    </li>
</ul>