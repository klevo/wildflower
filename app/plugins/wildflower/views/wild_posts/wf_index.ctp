<div id="content">
<?php
	echo 
	$form->create('Post', array('url' => $here)),
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

<h2 class="section">Published Posts</h2>

<ul class="list-of-posts list">
    <?php foreach ($posts as $post) { ?>
        <li class="post-row">
            <span class="row-check"><?php echo $form->checkbox('id.' . $post['WildPost']['id']) ?></span>
            <small><?php echo $time->format('j M', $post['WildPost']['created']) ?></small>
            <span class="title-row"><?php echo $html->link($post['WildPost']['title'], array('action' => 'wf_edit', $post['WildPost']['id']), array('title' => 'Edit this post.')) ?></span>
            <span class="row-actions"><?php echo $html->link('View', WildPost::getUrl($post['WildPost']['uuid']), array('class' => 'permalink', 'rel' => 'permalink', 'title' => 'View this post.')) ?></span>
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
        '<span>' . __('Write a new post', true) . '</span>',
        array('action' => 'add'),
        array('class' => 'add', 'escape' => false)) ?></li>
    <li>
        <?php
            echo
            $form->create('WildPost', array('action' => 'search', 'class' => 'search')),
            $form->input('query', array('label' => __('Find a post by typing', true))),
            $form->end();
        ?>
    </li>
    <li>
        <p><?php echo $html->link('Blog RSS Feed', '/' . Configure::read('Wildflower.blogIndex') . '/feed', array('id' => 'posts-feed')) ?></p>
    </li>
</ul>


    
