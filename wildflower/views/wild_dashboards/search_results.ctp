
<?php if (isset($results) && !empty($results)) { ?>
<ul class="search-results">
<?php
    foreach ($results as $item) {
        $row = '';
        if (isset($item['Page'])) {
            $row = $html->link($item['Page']['title'], 
                array('controller' => 'pages', 'action' => 'edit', $item['Page']['id']));   
        } else if (isset($item['Post'])) {
            $row = $html->link($item['Post']['title'], 
                array('controller' => 'posts', 'action' => 'edit', $item['Post']['id']));
        } else {
            continue;
        }
        
        echo "<li>$row</li>\n";
    }
?>
</ul>
<?php } else { ?>
    <div class="search-results nomatch">No matches</div>
<?php } ?>
