<?php 
    $partialLayout->switchToEditorMode();
    $partialLayout->setLayoutVar('publishedLink', $html->link(FULL_BASE_URL . $this->base . '/' . Configure::read('Wildflower.postsParent') . '/' . $this->data['WildPost']['slug'], '/' . Configure::read('Wildflower.postsParent') . '/' . $this->data['WildPost']['slug']));
    $session->flash();
    
    echo 
    $form->create('WildPost', array('url' => $html->url(array('action' => 'wf_update', 'base' => false)), 'class' => 'editor_form'));
?>

<div id="title-content">
    <?php
        echo
        $form->input('title', array(
            'label' => __('Post title', true),
            'div' => array('class' => 'input'))),
        $form->input('content', array(
            'type' => 'textarea',
            'class' => 'tinymce',
            'rows' => '25',
            'label' => __('Body', true),
            'div' => array('class' => 'input editor'))),
        '<div>',
        $form->hidden('id'),
        $form->hidden('draft'),
        '</div>';
    ?>
    
    <div id="edit-buttons">
        <?php echo $this->element('wf_edit_buttons'); ?>
    </div>
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
    <div class="cancel-edit cancel-section"><?php echo $html->link(__('Go back to post edit', true), '#Cancel'); ?></div>
</div>

<?php echo $form->end(); ?>
    

<?php $partialLayout->blockStart('sidebar'); ?>
    <li class="main_sidebar">
        <?php echo $html->link(
            '<span>Write a new post</span>', 
            array('action' => 'wf_create'),
            array('class' => 'add', 'escape' => false)); ?>
    </li>
    <li class="main_sidebar">
        <ul class="sidebar-menu-alt edit-sections-menu">
            <li><?php echo $html->link('Categorize this post', array('action' => 'categorize', $this->data['WildPost']['id'])); ?></li>
            <li><?php echo $html->link('Options <small>like status, publish date, etc.</small>', array('action' => 'options', $this->data['WildPost']['id']), array('escape' => false)); ?></li>
            <li><?php echo $html->link('Browse older versions', '#Revisions', array('rel' => 'post-revisions')); ?></li>
            <li><?php echo $html->link("Comments ({$this->data['WildPost']['wild_comment_count']})", array('action' => 'comments', $this->data['WildPost']['id'])); ?></li>
        </ul>
    </li>
<?php $partialLayout->blockEnd(); ?>
