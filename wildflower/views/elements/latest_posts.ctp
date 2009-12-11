<?php

$posts = $this->requestAction('/posts/latest');
foreach($posts as $post) {
    echo $post['Post']['title'];
}


?>
