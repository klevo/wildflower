<h2 class="section">Welcome to <?php echo $siteName ?> administration</h2>

<p>Dashboard shows a summary of the latest happening on your site.</p>

<h3>Latest comments</h3>
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

<h3>Messages from the contact form</h3>
<?php
    if (empty($messages)) {
        echo '<p>No messages from contact form yet.</p>';
    } else {
        echo $list->create($messages, array('model' => 'WildMessage', 'class' => 'list', 'element' => 'admin_messages_list_item'));
    }
?>


<?php $partialLayout->blockStart('sidebar'); ?>
    <li>
        <?php echo $html->link(
            '<span>' . __('Write a new post', true) . '</span>', 
            array('controller' => 'wild_posts', 'action' => 'wf_create'),
            array('class' => 'add', 'escape' => false)); ?>
        <?php echo $html->link(
            '<span>' . __('Write a new page', true) . '</span>', 
            array('controller' => 'wild_pages', 'action' => 'wf_create'),
            array('class' => 'add', 'escape' => false)); ?>
    </li>
    <li>
        <?php
            echo
            $form->create('WildDashboard', array('url' => $html->url(array('action' => 'wf_search', 'base' => false)), 'class' => 'search')),
            $form->input('query', array('label' => __('Find a page or post by typing', true), 'id' => 'SearchQuery')),
            $form->end();
        ?>
    </li>
<?php $partialLayout->blockEnd(); ?>
