<h2 class="section">Welcome to <?php echo $siteName ?> administration</h2>

<p><?php __('Recently added or changed content:'); ?></p>

<?php
    $labels = array(
        'Page' => array(
            'name' => __('Page', true),
            'link' => '/pages/edit/:id',
            'class' => 'page'
        ), 
        'Post' => array(
            'name' => __('Post', true),
            'link' => '/posts/edit/:id',
            'class' => 'post'
        ),
        'Comment' => array(
            'name' => __('Comment', true),
            'link' => '/comments/edit/:id',
            'class' => 'comment'
        ),
        'Message' => array(
            'name' => __('Message', true),
            'link' => '/messages/view/:id',
            'class' => 'message'
        ),
        'Asset' => array(
            'name' => __('File', true),
            'link' => '/assets/edit/:id',
            'class' => 'file'
        ),
    );
?>

<?php if (empty($items)): ?>
    <p><?php __('Nothing happened yet.'); ?></p>;
<?php else: ?>
    <table class="recently_changed" cellspacing="0">
        <tbody>
            <?php foreach ($items as $item): ?>
            <tr class="recent_<?php echo $labels[$item['class']]['class']; ?>">
                <th><?php echo $labels[$item['class']]['name']; ?></th>
                <td>
                <?php 
                    $label = empty($item['item']['title']) ? '<em>untitled</em>' : hsc($item['item']['title']);
                    $url = '/' . Configure::read('Wildflower.prefix') . '/' . r(':id', $item['item']['id'], $labels[$item['class']]['link']);
                    if ($item['class'] == 'Comment') {
                        $url = array('controller' => 'comments', 'action' => 'index', '#comment-' . $item['item']['id']);
                    }
                    echo $html->link($label, $url, array('escape' => false)); 
                ?>
                </td>
                <td class="recent_date"><?php echo $time->niceShort($item['item']['updated']); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>


<?php $partialLayout->blockStart('sidebar'); ?>
    <li>
        <?php
            echo
            $form->create('Dashboard', array('url' => $html->url(array('action' => 'admin_search', 'base' => false)), 'class' => 'search')),
            $form->input('query', array('label' => __('Find a page or post by typing', true), 'id' => 'SearchQuery')),
            $form->end();
        ?>
    </li>
    <!-- 
    <li class="main_sidebar category_sidebar">
        <h4 class="sidebar_heading">User activity</h4>
        <ul>
        <?php foreach ($users as $user): ?>
            <?php
                $userText = __(' logged in ', true) . $time->niceShort($user['User']['last_login']);
                if (empty($user['User']['last_login'])) {
                    $userText = __(' not seen recently.', true);
                }
            ?>
            <li><?php echo $user['User']['name'], $userText; ?></li>
        <?php endforeach; ?>
        </ul>
    </li>
-->
<?php $partialLayout->blockEnd(); ?>
