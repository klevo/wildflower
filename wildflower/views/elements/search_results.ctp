
<?php if (isset($results) && !empty($results)) { ?>
<?php $model = ucwords(Inflector::singularize($this->params['controller'])) ?>
<ul class="search-results">
    <?php 
    foreach ($results as $post) {
        echo '<li>' . 
           $html->link($post[$model]['title'], array('action' => 'edit', $post[$model]['id'])) . '</li>';
    }
    ?>
</ul>
<?php } else { ?>
    <div class="search-results nomatch">No matches</div>
<?php } ?>
