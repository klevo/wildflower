<?php
	echo 
	$form->create('WildPost', array('action' => 'mass_update')),
	$form->input('show', array(
	    'type' => 'select',
	    'options' => array(
	        'published' => 'Published',
	        'draft' => 'Not published (drafts)',
	        'category' => 'From Category',
	    ),
	    'label' => 'Show ',
	    'div' => array('id' => 'show-posts'),
	));
?>

<h2 class="section"><?php __('Blog Posts'); ?></h2>

<?php echo $this->element('wf_select_actions'); ?>

<ul class="list-of-posts list">
    <?php foreach ($posts as $post): ?>
        <li class="post-row actions-handle">
            <span class="row-check"><?php echo $form->checkbox('id.' . $post['WildPost']['id']) ?></span>
            <?php
                $draftStatus = '';
                if ($post['WildPost']['draft']) {
                    $draftStatus = '<abbr title="This post is not published, therefore not visible to the public." class="draft-status">(Draft)</abbr> ';
                }
            ?>
            <span class="title-row"><?php echo $draftStatus, $html->link($post['WildPost']['title'], array('action' => 'wf_edit', $post['WildPost']['id']), array('title' => __('Edit this post.', true))) ?></span>
            <span class="post-date"><?php echo $html->link($time->format('j M y', $post['WildPost']['created']), array('action' => 'options', $post['WildPost']['id']), array('title' => __('Change post options.', true))); ?></span>
            <div class="post-categories">
            <?php
                // Post categories list
                $categories = Set::extract($post['WildCategory'], '{n}.title');
                if (!empty($categories)) {
                    $categories = join(', ', $categories);
                } else {
                    $categories = 'Uncategorized';
                }
                echo $html->link($categories, array('action' => 'categorize', $post['WildPost']['id']), array('title' => __('Categorize this post.', true)));
            ?>
            </div>
            <div class="post-comments"><?php echo $html->link($post['WildPost']['wild_comment_count'], array('action' => 'comments', $post['WildPost']['id']), array('title' => __('Manage this post\'s comments.', true)))?></div>
            <span class="row-actions"><?php echo $html->link('View', WildPost::getUrl($post['WildPost']['slug']), array('class' => '', 'rel' => 'permalink', 'title' => __('View this post.', true))) ?></span>
            <span class="cleaner"></span>
        </li>
    <?php endforeach; ?>
</ul>

<?php
    echo
    $this->element('wf_select_actions'), 
	$this->element('wf_pagination'),
    $form->end();
?>


<?php $partialLayout->blockStart('sidebar'); ?>
    <li>
        <?php echo $this->element('../wild_posts/_sidebar_search'); ?>
    </li>
    <li><?php echo $html->link(
        '<span>' . __('Write a new post', true) . '</span>',
        array('action' => 'wf_create'),
        array('class' => 'add', 'escape' => false)) ?>
    </li>
<?php $partialLayout->blockEnd(); ?>


    
