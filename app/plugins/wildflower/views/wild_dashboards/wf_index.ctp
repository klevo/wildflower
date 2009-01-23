<h2 class="section">Welcome to <?php echo $siteName ?> administration</h2>

<p>Dashboard shows a summary of the latest happening on your site.</p>

<h3>Pages modified recently</h3>
<?php
    if (empty($pages)) {
        echo '<p>No pages yet.</p>';
    } else {
        echo '<ul class="pages-list list">';
        foreach ($pages as $page) {
            echo "<li><div class=\"list-item\">",
                $html->link($page['WildPage']['title'], array('controller' => 'wild_pages', 'action' => 'edit', $page['WildPage']['id'])), 
                '</div></li>';    
        }
        echo '</ul>';
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
