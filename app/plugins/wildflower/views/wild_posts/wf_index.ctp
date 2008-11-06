<div id="content">
<?php
	echo 
	$form->create('Post'),
	$form->input('show', array(
	    'type' => 'select',
	    'options' => array(
	        'published' => 'Published',
	        'draft' => 'Not published (drafts)',
	        'category' => 'From Category',
	    ),
	    'label' => 'Show '
	));
?>

<h2>Published Posts</h2>

<ul class="list-of-posts">
    <?php foreach ($posts as $post) { ?>
        <li class="post-row">
            <span class="row-check"><?php echo $form->checkbox('id.' . $post['WildPost']['id']) ?></span>
            <small><?php echo $time->format('j M', $post['WildPost']['created']) ?></small>
            <span class="title-row"><?php echo $html->link($post['WildPost']['title'], array('action' => 'wf_edit', $post['WildPost']['id'])) ?></span>
            <span class="row-actions"><?php echo $html->link('View', WildPost::getUrl($post['WildPost']['slug']), array('class' => 'permalink', 'rel' => 'permalink', 'title' => 'View this post in a new browser tab.')) ?></span>
            <?php
                $categories = Set::extract($post['WildCategory'], '{n}.title');
                foreach ($categories as &$category) {
                    //$category = $html->link($category['name'], array(''))
                }
                if (!empty($categories)) {
                    $categories = join(', ', $categories);

                    echo '<div class="post-categories">' . $categories . '</div>';
                }
            ?>
        </li>
    <?php } ?>
</ul>

<?php
    echo 
    $form->input('action', array(
	    'type' => 'select',
	    'options' => array(
	        'choose' => '(choose an action)',
	        'publish' => 'Publish',
	        'draft' => 'Unpublish',
	        'delete' => 'Delete',
	    ),
	    'label' => 'With selected '
	)),
	$this->element('wf_pagination'),
    $form->end();
?>
</div>

<ul id="sidebar">
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
    <li>
        <p><?php echo $html->link('Blog RSS Feed', '/' . WILDFLOWER_POSTS_INDEX . '/feed') ?></p>
    </li>
</ul>


    
