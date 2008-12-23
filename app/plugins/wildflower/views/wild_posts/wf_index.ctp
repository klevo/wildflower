
    <?php
    	echo 
    	$form->create('Post', array('url' => $html->url(array('action' => 'wf_mass_update', 'base' => false)))),
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
        <?php foreach ($posts as $post) { ?>
            <li class="post-row">
                <span class="row-check"><?php echo $form->checkbox('id.' . $post['WildPost']['id']) ?></span>
                <small><?php echo $time->format('j M', $post['WildPost']['created']) ?></small>
                <span class="title-row"><?php echo $html->link($post['WildPost']['title'], array('action' => 'wf_edit', $post['WildPost']['id']), array('title' => __('Edit this post.', true))) ?></span>
                <span class="row-actions"><?php echo $html->link('View', WildPost::getUrl($post['WildPost']['uuid']), array('class' => 'permalink', 'rel' => 'permalink', 'title' => __('View this post.', true))) ?></span>
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
        $this->element('wf_select_actions'), 
    	$this->element('wf_pagination'),
        $form->end();
    ?>


<?php $partialLayout->blockStart('sidebar'); ?>
    <li><?php echo $html->link(
        '<span>' . __('Write a new post', true) . '</span>',
        array('action' => 'wf_create'),
        array('class' => 'add', 'escape' => false)) ?>
    </li>
    <li>
        <ul class="sidebar-menu">
            <li><?php echo $html->link(__('All Posts', true), array('action' => 'wf_index'), array('class' => 'back-to-all current')); ?></li>
            <li><?php echo $html->link(__('Comments', true), array('controller' => 'wild_comments', 'action' => 'wf_index')); ?></li>
            <!-- <li><?php echo $html->link('Categories', array('action' => 'wf_categories')); ?></li> -->
        </ul>
    </li>
    <li>
    <?php
        echo
        $form->create('WildPost', array('action' => 'search', 'class' => 'search')),
        $form->input('query', array('label' => __('Find a post by typing', true))),
        $form->end();
    ?>
    </li>
    <li>
        <p><?php echo $html->link(__('Blog RSS Feed', true), '/' . Configure::read('Wildflower.blogIndex') . '/feed', array('id' => 'posts-feed')); ?></p>
    </li>
<?php $partialLayout->blockEnd(); ?>


    
