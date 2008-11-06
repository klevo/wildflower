<h2 class="dash-top">Welcome to <?php echo $siteName ?> administration</h2>

<p>Dashboard shows a summary of the latest happening on your site.</p>

<h3 class="section-title">Latest comments</h3>
<?php
    if (empty($comments)) {
        echo '<p>No comments yet.</p>';
    } else {
        echo '<ul class="list">';
        foreach ($comments as $comment) {
            $attr = '';
            if (ListHelper::isOdd()) {
                $attr = ' class="odd"';
            }
            echo "<li$attr><div class=\"list-item\">",
                $html->link($comment['WildComment']['name'] . ' on ' . $comment['WildPost']['title'], '/' . Configure::read('Routing.admin') . '/comments#comment-' . $comment['WildComment']['id']), 
                '</div></li>';    
        }
        echo '</ul>';
    }
?>

<h3 class="section-title">Messages from the contact form</h3>
<?php
    if (empty($messages)) {
        echo '<p>No messages from contact form yet.</p>';
    } else {
        echo $list->create($messages, array('model' => 'WildMessage', 'class' => 'list', 'element' => 'admin_messages_list_item'));
    }
?>
