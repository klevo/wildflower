<h2 class="section">Welcome to <?php echo $siteName ?> administration</h2>

<p>Dashboard shows a summary of the latest happening on your site.</p>

<?php
    if (empty($items)) {
        echo '<p>', __('Nothing happened yet.', true), '</p>';
    } else {
        var_dump($items);
    }
?>


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
