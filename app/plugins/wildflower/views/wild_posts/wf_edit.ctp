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
        'label' => false,
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
</div>   

<div id="post-options">
    <h2 class="section">Post Options</h2>
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

<div class="submit" id="save-preview">
    <input type="submit" value="<?php __('Preview'); ?>" />
</div>
<?php if ($isDraft): ?>    
<div class="submit" id="save-draft">
    <input type="submit" value="<?php __('Save, but don\'t publish'); ?>" name="data[__save][draft]" />
</div>
<div class="submit" id="save-publish">
    <input type="submit" value="<?php __('Publish'); ?>" name="data[__save][publish]" />
</div>
<?php else: ?>
<div class="submit" id="save-draft">
    <input type="submit" value="<?php __('Save changes'); ?>" />
</div>
<?php endif; ?>
<div class="cancel-edit"> <?php __('or'); ?> <?php echo $html->link(__('Discard changes and go back to all posts', true), array('action' => 'wf_index')); ?></div>

<?php echo $form->end(); ?>

<p class="post-info">
    This post is <?php if ($isDraft): ?>not published, therefore not visible to the public.<?php else: ?>published and visible to the public at <?php echo $html->link(FULL_BASE_URL . $this->base . WildPost::getUrl($this->data['WildPost']['uuid']), WildPost::getUrl($this->data['WildPost']['uuid'])); ?><?php endif; ?>. Latest changes were made <?php echo $time->nice($this->data['WildPost']['updated']); ?> by <?php echo hsc($this->data['WildUser']['name']); ?>.
</p>
    

<?php $partialLayout->blockStart('sidebar'); ?>
    <li>
        <?php echo $html->link(
            '<span>Write a new post</span>', 
            array('action' => 'wf_create'),
            array('class' => 'add', 'escape' => false)); ?>
    </li>
    <li>
        <?php echo $this->element('../wild_posts/_sidebar_search'); ?>
    </li>
    <li>
        <ul class="sidebar-menu-alt edit-sections-menu">
            <li><?php echo $html->link('Categorize this post', '#Categories', array('rel' => 'post-categories')); ?></li>
            <li><?php echo $html->link('Options', '#Options', array('rel' => 'post-options')); ?></li>
            <li><?php echo $html->link('Browse older versions', '#Revisions', array('rel' => 'post-revisions')); ?></li>
            <li><?php echo $html->link('View published', WildPost::getUrl($this->data['WildPost']['uuid']), array('class' => 'permalink')); ?></li>
        </ul>
    </li>
    <li>
        <ul class="sidebar-menu">
            <li><?php echo $html->link('All Posts', array('action' => 'wf_index'), array('class' => 'back-to-all')); ?></li>
        </ul>
    </li>
<?php $partialLayout->blockEnd(); ?>
