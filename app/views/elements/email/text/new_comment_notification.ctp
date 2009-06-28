A new comment has been posted at <?php echo FULL_BASE_URL; ?>. 

Manage post's comments at <?php echo $html->url('/' . Configure::read('Wildflower.prefix') . '/posts/comments/' . $postId . '/comments', true); ?> 

From: <?php echo $name; ?> <<?php echo $email; ?>> 
Message: 
<?php echo $message; ?> 
 