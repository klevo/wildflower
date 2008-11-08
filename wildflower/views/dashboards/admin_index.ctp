<h2 class="dash-top"><?php printf(__('Welcome to %s administration',true),$siteName) ; ?></h2>

<p><?php echo __('Dashboard shows a summary of the latest happening on your site').'.' ?></p>

<h3 class="section-title"><?php echo __('Latest comments') ?></h3>
<?php
    if (empty($comments)) {
        echo '<p>'.__('No comments yet') . '.' . '</p>';
    } else {
        echo '<ul class="list">';
        foreach ($comments as $comment) {
            $attr = '';
            if (ListHelper::isOdd()) {
                $attr = ' class="odd"';
            }
            echo "<li$attr><div class=\"list-item\">",
                $html->link(sprintf(__('%s on %s',true),$comment['Comment']['name'],$comment['Post']['title']), '/' . Configure::read('Routing.admin') . '/comments#comment-' . $comment['Comment']['id']),
                '</div></li>';    
        }
        echo '</ul>';
    }
?>

<h3 class="section-title"><?php echo __('Messages from the contact form') ?></h3>
<?php
    if (empty($messages)) {
        echo '<p>' . __('No messages from contact form yet') . '.</p>';
    } else {
        echo $list->create($messages, array('model' => 'Message', 'class' => 'list', 'element' => 'admin_messages_list_item'));
    }
?>
