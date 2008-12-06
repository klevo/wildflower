<div id="content">
    <?php 
        $session->flash();
        
        echo 
        $form->create('WildPost', array('url' => $html->url(array('action' => 'wf_update', 'base' => false))));
    ?>
    
    <div id="title-content">
    <?php
        echo
        $form->input('title', array(
            'between' => '<br />',
            'tabindex' => '1',
            'label' => __('Post Title', true),
            'div' => array('class' => 'input title-input'))),
        $form->input('content', array(
            'type' => 'textarea',
            'tabindex' => '2',
            'class' => 'tinymce',
            'rows' => '25',
            'label' => __('Content', true),
            'between' => '<br />',
            'div' => array('class' => 'input editor'))),
        '<div>',
        $form->hidden('id'),
        '</div>';
    ?>
    </div>
    
    <div id="post-categories">
        <h2 class="section">Post under following categories</h2>
        <ul>
        <?php foreach ($categories as $id => $label): ?>
            <?php $checked = in_array($id, $inCategories) ? ' checked="checked"' : ''; ?>
            <li>
                <input id="WildCategoryWildCategory<?php echo $id ?>" type="checkbox" value="<?php echo $id ?>" name="data[WildCategory][WildCategory][]"<?php echo $checked ?> />
                <label for="WildCategoryWildCategory<?php echo $id ?>"><?php echo hsc($label) ?></label>
            </li>
        <?php endforeach; ?>
        </ul>
        
        <h2>Options</h2>
        <?php
            echo 
            $form->input('draft', array('type' => 'select', 'between' => '<br />', 'label' => 'Status', 'options' => WildPost::getStatusOptions())),
            $form->input('description_meta_tag', array('between' => '<br />', 'type' => 'textarea', 'rows' => 6, 'cols' => 27, 'tabindex' => '4')),
            //$form->input('slug', array('between' => '<br />', 'label' => 'URL slug', 'size' => 30)),
            $form->input('created', array('between' => '<br />'));
        ?>

    </div>    
    
    <div id="post-revisions">
        <h2 class="section">Older versions of this post</h2>
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
    
    <div id="post-preview">
        <h2 class="section">Post Preview</h2>
        <object data="<?php echo $html->url(array('action' => 'wf_preview')); ?>" type="text/html"></object>
    </div>
        
    <div class="submit" id="save-draft">
        <input type="submit" value="<?php __('Save, but don\'t publish'); ?>" name="data[__save][draft]" />
    </div>
    <div class="submit" id="save-publish">
        <input type="submit" value="<?php __('Publish'); ?>" name="data[__save][publish]" />
    </div>
    <div class="cancel-edit"> or <?php echo $html->link(__('Cancel', true), array('action' => 'wf_index')); ?></div>
    
    <?php echo $form->end(); ?>
    
</div>

<ul id="sidebar">
    <li>
        <ul class="sidebar-menu">
            <li><?php echo $html->link('All Posts', array('action' => 'wf_index'), array('class' => 'back-to-all')); ?></li>
            <li><?php echo $html->link('Title & Content', array('action' => 'wf_edit'), array('class' => 'current', 'rel' => 'title-content')); ?></li>
            <li><?php echo $html->link('Categories & Options', '#Categories', array('rel' => 'post-categories')); ?></li>
            <li><?php echo $html->link('Revisions', '#Revisions', array('rel' => 'post-revisions')); ?></li>
            <li><?php echo $html->link('Preview', '#Preview', array('rel' => 'post-preview')); ?></li>
            <li><?php echo $html->link('View', WildPost::getUrl($this->data['WildPost']['uuid']), array('class' => 'permalink')); ?></li>
        </ul>
    </li>
    <li>
        <?php echo $html->link(
            '<span>Write a new post</span>', 
            array('action' => 'wf_create'),
            array('class' => 'add', 'escape' => false)); ?>
    </li>
    <li>
        <?php
            echo
            $form->create('WildPost'),
            $form->input('query', array('label' => __('Find a post by typing', true))),
            $form->end();
        ?>
    </li>
</ul>
