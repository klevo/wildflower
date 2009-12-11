<div class="sidebar_posts">
<h2> Latest Posts</h2>
<br/>
<?php
$posts = $this->requestAction('/posts/latest/'.$categorySlug.'/'.$categoryLimit);
//pr($posts);
if(!empty($posts)):
foreach ($posts as $post) :
?>
        <h2><?php echo $html->link($post['Post']['title'], Post::getUrl($post['Post']['slug'])); ?></h2>
        <?php echo $html->link(' >>', Post::getUrl($post['Post']['slug']), array('class' => 'post_link'));      ?>
        <small class="post-date">Posted <?php echo $time->niceShort($post['Post']['created']) ?></small>
<?php
endforeach;
else:
?>
No posts have been found
<?php
endif;
?>
</div>
<br/>
