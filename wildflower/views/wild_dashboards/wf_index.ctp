<h2 class="section">Welcome to <?php echo $siteName ?> administration</h2>

<p><?php __('Recently added or changed content:'); ?></p>

<?php
    $labels = array(
        'WildPage' => array(
            'name' => __('Page', true),
            'link' => '/pages/edit/:id'
        ), 
        'WildPost' => array(
            'name' => __('Post', true),
            'link' => '/posts/edit/:id'
        ),
        'WildComment' => array(
            'name' => __('Comment', true),
            'link' => '/comments/edit/:id'
        ),
        'WildMessage' => array(
            'name' => __('Message', true),
            'link' => '/messages/view/:id'
        ),
        'WildAsset' => array(
            'name' => __('File', true),
            'link' => '/assets/edit/:id'
        ),
    );
?>

<?php if (empty($items)): ?>
    <p><?php __('Nothing happened yet.'); ?></p>;
<?php else: ?>
    <table>
        <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
                <th><?php echo $labels[$item['class']]['name']; ?></th>
                <td>
                <?php 
                    $label = empty($item['item']['title']) ? '<em>untitled</em>' : hsc($item['item']['title']);
                    $url = '/' . Configure::read('Wildflower.prefix') . '/' . r(':id', $item['item']['id'], $labels[$item['class']]['link']);
                    echo $html->link($label, $url, array('escape' => false)); 
                ?>
                </td>
                <td><?php echo $time->niceShort($item['item']['updated']); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>


<?php $partialLayout->blockStart('sidebar'); ?>
    <li>
        <?php
            echo
            $form->create('WildDashboard', array('url' => $html->url(array('action' => 'wf_search', 'base' => false)), 'class' => 'search')),
            $form->input('query', array('label' => __('Find a page or post by typing', true), 'id' => 'SearchQuery')),
            $form->end();
        ?>
    </li>
<?php $partialLayout->blockEnd(); ?>
