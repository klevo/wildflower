<div class="post" id="post-<?php echo $post['Post']['id']; ?>">
	<h2><?php echo $post['Post']['title']; ?></h2>
	<small class="post-date">Posted <?php echo $time->nice($post['Post']['created']); ?></small>
	
	<div class="entry"><?php echo $post['Post']['content']; ?></div>
	
	<?php if (!empty($post['Category'])) { ?>
	   <p class="postmeta">Posted in <?php echo $category->getList($post['Category']); ?>.</p>
	<?php } ?> 
	
	<?php echo $this->element('edit_this', array('id' => $post['Post']['id'])) ?>
</div>

<p><?php echo $html->link('Back to all posts', '/blog') ?></p>

<?php if (!empty($post['Comment'])) { ?>
<h3>Comments</h3>
<ol class="comments-list">
<?php foreach ($post['Comment'] as $comment) { ?>      
    <li id="comment-<?php echo $comment['id'] ?>">
        <p class="comment-metadata">Posted by <?php echo $comment['url'] ? $html->link($comment['name'], $comment['url']) : $comment['name'] ?> 
        <?php echo $time->timeAgoInWords($comment['created']) ?></p>
        
        <div><?php echo $textile->format($comment['content']) ?></div>
    </li>
<?php } ?>
</ol>
<?php } ?>

<h3>Post a comment</h3>
<?php
    if ($session->check('Message.flash')) {
        $session->flash();
    }
    
    $postUrl = Post::getUrl($post['Post']['slug']);
    echo $form->create('Comment', array('class' => 'comment-form', 'url' => $here, 'id' => 'PostAComment')),
        $form->input('name'),
        $form->input('email'),
        $form->input('url', array('label' => 'Website URL (optional)')),
        $form->input('content', array('label' => 'Message', 'type' => 'textbox')),
        '<div>',
        $form->hidden('post_id', array('value' => $post['Post']['id'])),
        '</div>',
        $form->hidden('Post.permalink', array('value' => $html->url($postUrl, true))),
        $form->submit('Post comment'),
        $form->end();
?>

