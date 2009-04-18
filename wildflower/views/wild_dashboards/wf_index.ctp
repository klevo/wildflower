<h2 class="section">Welcome to <?php echo $siteName ?> administration</h2>

<p><?php __('Recently added or changed content:'); ?></p>

<?php
    $labels = array(
        'WildPage' => __('Page', true), 
        'WildPost' => __('Post', true), 
        'WildComment' => __('Comment', true), 
        'WildMessage' => __('Message', true), 
        'WildAsset' => __('File', true), 
    );
?>

<?php if (empty($items)): ?>
    <p><?php __('Nothing happened yet.'); ?></p>;
<?php else: ?>
    <table>
        <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
                <th><?php echo $labels[$item['class']]; ?></th>
                <td><?php echo $item['item']['title']; ?></td>
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
